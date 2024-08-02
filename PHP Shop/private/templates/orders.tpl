<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		{head}
	</head>
	<body>
		<div id="container">
			{header}
			{menu}
			<div id="content">
				<b>Your Account</b>
				<p>
					<ul>
						<bloc::order>
							<li><a href="vieworder.php?orderid={id}">Order {id} ({date})</a></li>
						</bloc::order>
					</ul>
					<bloc::error>
						<b>{message}</b>
					</bloc::error>
				</p>
			</div>
		</div>
	</body>
</html>