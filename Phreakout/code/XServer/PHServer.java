
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.StringTokenizer;

/**
 * @author Viktor
 * Document created: 2004-01-27 21:07
 */
public class PHServer
{
	//Limit nr of connections (players)
	private int limit=-1;
	private int myPort=-1;
	private ServerSocket myServerSocket=null;
	private List players=new LinkedList();
	private List teamHighscore=new LinkedList();
	private List playerHighscore=new LinkedList();
	private Map games=new HashMap();
	public boolean debug=false;

	public static void main(String args[])
	{
		new PHServer(args);
	}

	private PHServer(String args[])
	{
		if((args.length<2)||(args[0].equals("-h")))
		{
			System.err.println("Phreakout Server\nUsage: java PHServer -p PORT [-l USER_LIMIT] [-h]\n\n\t-p The port to use for this server.\n\t-l Limit nr of connections to the server. Default is no limit.\n\t-h Display this help text.\n-d Display debug output");
		}
		else
		{
			for(int i=0;i<args.length;i++)
			{
				if(args[i].equals("-p"))
					myPort=Integer.parseInt(args[i + 1]);
				else if(args[i].equals("-l"))
					limit=Integer.parseInt(args[i + 1]);
				else if(args[i].equals("-d"))
					debug=true;
				else if (args[i].equals("-h"))
					System.err.println("Phreakout Server\nUsage: java PHServer -p PORT [-l USER_LIMIT] [-h]\n\n\t-p The port to use for this server.\n\t-l Limit nr of connections to the server. Default is no limit.\n\t-h Display this help text.\n\t-d Display debug output");
			}
			startServer();
			Listen listen= new Listen(this);
			listen.start();
			System.out.println("PHServer running on port " + myPort + ".");
		}
	}

	private boolean startServer()
	{
		try
		{
			myServerSocket= new ServerSocket(myPort);
		}
		catch(IOException io)
		{
			io.printStackTrace(System.err);
			return (false);
		}
		if(myServerSocket!=null)
			return(true);
		else
			return(false);
	}

	public synchronized String messageHandler(Player player, String message)
	{
		StringTokenizer st=new StringTokenizer(message.trim(), "|");
		if(st.hasMoreTokens())
		{
			String command=st.nextToken();
			if(debug)
				System.out.println("Recived - Client: "+player.getUserName() + ", Message: " + message+", Command: \""+command+"\"");
			if(command.equals("CONNECT"))
			{
				String name=st.nextToken();
				for(int i=0;i<players.size();i++)
				{
					Player p=(Player)players.get(i);
					if((p.getUserName()!=null)&&(p.getUserName().equals(name)))
					{
						return("CONNECT|ERROR");
					}
				}
				player.setUserName(name);
				player.sendData(createHostlist());
				sendUserlist();
				return("CONNECT|OK");
			}
			else if(command.equals("QCONNECT"))
			{
				if(player.getGame()!=null)
					messageHandler(player,"QGAME");
				players.remove(player);
				sendUserlist();
			}
			else if(command.equals("HOST"))
			{
				String name=st.nextToken();
				if(games.containsKey(name))
				{
					return("HOST|ERROR");
				}
				else
				{
					Game game=new Game(name,Integer.parseInt(st.nextToken()),player);
					game.open();
					games.put(name,game);
					player.setGame(game);
					game.toAll("PLAYERLIST" + playerList(game));
					sendHostlist();
					return("HOST|OK");
				}
			}
			else if(command.equals("QGAME"))
			{
				if(games.containsValue(player.getGame()))
				{
					Game game=(Game)games.remove(player.getGame().getName());
					List players=game.getPlayers();
					for(int i=0;i<players.size();i++)
					{
						Player p=(Player)players.get(i);
						p.setGame(null);
						p.sendData("QGAME");
					}
					sendHostlist();
				}
			}
			else if(command.equals("JOIN"))
			{
				String name=st.nextToken();
				Game g=((Game)games.get(name));
				if((g.isOpen())&&(g.addPlayer(player)))
				{
					player.setGame(g);
					g.toAll("PLAYERLIST" + playerList(g));
					sendHostlist();
					return("JOIN|OK");
				}
				else
				{
					return("JOIN|ERROR");
				}
			}
			else if(command.equals("QJOIN"))
			{
				if(games.containsValue(player.getGame()))
				{
					player.getGame().removePlayer(player);
	       	player.getGame().toAll("PLAYERLIST"+playerList(player.getGame()));
					player.setGame(null);
				}
			}
			else if(command.equals("HOSTLIST"))
			{
				return(createHostlist());
			}
			else if(command.equals("REPLICATE"))
			{
				if(player.getGame()!=null)
				{
					player.getGame().replicate(player, st.nextToken());
					return("REPLICATE|OK");
				}
				else
				{
					return("REPLICATE|ERROR");
				}
			}
			else if(command.equals("HIGHSCORE"))
			{
				if(st.countTokens()>1)
				{
					teamHighscore.add(new Score(st.nextToken(),Integer.parseInt(st.nextToken())));
					while(st.hasMoreTokens())
						playerHighscore.add(new Score(st.nextToken(),Integer.parseInt(st.nextToken())));
					Collections.sort(teamHighscore);
					Collections.sort(playerHighscore);
				}
				else
				{
					player.sendData(createHighscoreList());
				}
			}
			else if(command.equals("START"))
			{
				Game game=player.getGame();
				if(game!=null)
				{
					game.close();
					game.toAll("START|"+st.nextToken()+playerList(game));
				}
				else
					return("START|ERROR");
				return("OK");
			}
			else if(command.equals("SYNC"))
		  {
				Game game=player.getGame();
				if(game!=null)
					game.replicate(player,message);
				return("OK");
			}
			else if(command.equals("PADDLE"))
			{
				Game game=player.getGame();
				if(game!=null)
					game.replicate(player,message);
				return("OK");
			}
			else if(command.equals("CHAT"))
			{
				if(player.getGame()==null)
					toLobby(message);
				else
					player.getGame().toAll(message);
				return("OK");
			}
			else
			{
				return("ERROR");
			}
		}
		return("NULL");
	}

	private String createHighscoreList()
	{
		String tmp="HIGHSCORE|TEAM";
		Score score=null;
		int i=0;
		for(i=0;(i<teamHighscore.size())&&(i<28);i++)
		{
			score=(Score)teamHighscore.get(i);
			tmp+="|"+score.getName()+"|"+score.getScore();
		}
		tmp+="|PLAYER";
		for(i=0;(i<playerHighscore.size())&&(i<28);i++)
		{
			score=(Score)playerHighscore.get(i);
			tmp+="|"+score.getName()+"|"+score.getScore();
		}
		return(tmp);
	}

	private void toLobby(String message)
	{
		Player player=null;
		Iterator iter=players.iterator();
		while(iter.hasNext())
		{
			player=(Player)iter.next();
			if(player.getGame()==null)
				player.sendData(message);
		}
	}

	private String playerList(Game game)
	{
		String tmp="";
		List players=game.getPlayers();
		for(int i=0;i<players.size();i++)
			tmp+="|"+((Player)players.get(i)).getUserName();
		return(tmp);
	}

	private void sendUserlist()
	{
		String userlist="USERLIST";
		Player player=null;
		int i=0;
		for(i=0;i<players.size();i++)
		{
			player=(Player)players.get(i);
			if(player.getUserName()!=null)
				userlist+="|"+player.getUserName();
		}
		for(i=0;i<players.size();i++)
		{
			player=(Player)players.get(i);
			if(player.getGame()==null)
				player.sendData(userlist);
		}
	}

	private void sendHostlist()
	{
		String hostlist=createHostlist();
		Player player=null;
		Game game=null;
		for(int i=0;i<players.size();i++)
		{
			player=(Player)players.get(i);
			game=player.getGame();
			if(player.isDead())
			{
				if(game!=null)
				{
					if(game.isHost(player))
						games.remove(game);
					game.removePlayer(player);
				}
				players.remove(player);
			}
			else if(game==null)
			{
				player.sendData(hostlist);
			}
		}
	}

	private String createHostlist()
	{
		String tmp="HOSTLIST";
		Game game=null;
		List players=null;
		int i=0;
		Iterator iter=games.values().iterator();
		while(iter.hasNext())
		{
			game=(Game)iter.next();
			tmp+="|"+game.getName()+"+"+game.getHost().getUserName()+"+"+game.isOpen();
		}
		return(tmp);
	}

	class Listen extends Thread
	{
		private PHServer parent=null;
		public Listen(PHServer parent)
		{
			this.parent=parent;
		}
		public void run()
		{
			try
			{
				Socket s=null;
				while(true)
				{
					s=myServerSocket.accept();
					if(debug)
						System.out.println("User Connected");
					if((limit==-1)||(players.size()<limit))
						players.add(new Player(parent, s));
				}
			}
			catch(Exception io)
			{
				io.printStackTrace(System.err);
			}
		}
	}
}

class Score implements Comparable
{
	private int score;
	private String name;

	public Score(String name, int score)
	{
		this.name=name;
		this.score=score;
	}

	public int compareTo(Object o)
	throws ClassCastException
	{
		if(!(o instanceof Score))
			throw(new ClassCastException("Not a score object"));
		return(((Score)o).getScore()-score);
	}

	public int getScore()
	{
		return(score);
	}
	
	public String getName()
	{
		return(name);
	}
}

class Game
{
	private String name=null;
	private Player host=null;
	private boolean open=false;
	private List players=new ArrayList();
	private int limit=-1;

	public Game(String name, int limit, Player host)
	{
		this.host=host;
		this.name=name;
		players.add(host);
		this.limit=limit;
	}

	public boolean isOpen()
	{
		return(open);
	}

	public void open()
	{
		open=true;
	}

	public void close()
	{
		open=false;
	}

	public boolean isHost(Player player)
	{
		return(this.host==player);
	}

	public Player getHost()
	{
		return(host);
	}

	public String getName()
	{
		return(name);
	}

	public boolean addPlayer(Player player)
	{
		if(!(players.size()==limit))
			return(players.add(player));
		else
			return(false);
	}

	public boolean removePlayer(Player player)
	{
		return(players.remove(player));
	}

	public List getPlayers()
	{
		return(players);
	}

	public void toAll(String message)
	{
		for(int i=0;i<players.size();i++)
			((Player)players.get(i)).sendData(message);
	}

	public void replicate(Player sender, String message)
	{
		Player player=null;
		for(int i=0;i<players.size();i++)
		{
			player=(Player)players.get(i);
			if(player!=sender)
				player.sendData(message);
		}
	}

	public String getIP()
	{
		return(host.getIP());
	}
}

class Player extends Thread
{
	private Socket mySocket=null;
	private PrintWriter pw=null;
	private PHServer parent=null;
	private String name=null;
	private Game game=null;

	public Player(PHServer parent, Socket socket)
	{
		this.parent=parent;
		this.mySocket=socket;
		try
		{
			pw=new PrintWriter(mySocket.getOutputStream());
		}
		catch (IOException io)
		{
			io.printStackTrace(System.err);
		}
		start();
	}

	public void setUserName(String name)
	{
		this.name=name;
	}

	public String getUserName()
	{
		return(name);
	}

	public void setGame(Game game)
	{
		this.game=game;
	}

	public Game getGame()
	{
		return(game);
	}

	public boolean isDead()
	{
		return(mySocket.isClosed());
	}

	public void sendData(String data)
	{
		if(parent.debug)
			System.out.println("Sending - Client: " + name + ", Data: " + data);
		pw.print(data.trim()+"\0");
		pw.flush();
	}

	public String getIP()
	{
		return(mySocket.getInetAddress().getHostAddress());
	}

	public void run()
	{
		try
		{
			BufferedReader br=new BufferedReader(new InputStreamReader(mySocket.getInputStream()));
			String line=null;
			while((line=br.readLine())!=null)
			{
				sendData(parent.messageHandler(this,line));
			}
		}
		catch(IOException io)
		{
		}
		finally
		{
			try
			{
				parent.messageHandler(this,"QCONNECT");
				mySocket.close();
			}
			catch(Exception e)
			{
			}
		}
	}
}
