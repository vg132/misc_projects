<?php
	require_once("../private/functions.php");
	require_once("../private/database.php");
	require_once("../private/shopsql.php");
	require_once("../private/Currency.php");
	require_once("../private/Item.php");
	require_once("../private/CategoryGroup.php");
	require_once("../private/Category.php");
	require_once("../private/Region.php");
	require_once("../private/ProductGroup.php");

	//Build basic admin page
	$db=new Database;
	$sql=new ShopSQL;

	$TPLN=new TPLN;
	$TPLN->Open("../private/templates/admin/admin.tpl");
	
	$TPLN->IncludeFile("menu","../private/templates/admin/menu.tpl");
	$TPLN->Parse("header",getHeader());
	$TPLN->Parse("head",getHead("Admin"));

	if(isset($_REQUEST["id"]))
	{
		//create a new database object
		$db->connect();

		switch($_REQUEST["id"])
		{
			case 1:
				$TPLN->Parse("body_content",ProductGroup());
				$TPLN->Parse("admin_type", "- Product Groups");
				break;
			case 2:
				$TPLN->Parse("body_content",Region());
				$TPLN->Parse("admin_type", "- Regions");
				break;
			case 3:
				$TPLN->Parse("body_content",Category());
				$TPLN->Parse("admin_type","- Categorys");
				break;
			case 4:
				$TPLN->Parse("body_content",AddItem());
				$TPLN->Parse("admin_type","- Add Item");
				break;
			case 5:
				break;
			case 6:
				$TPLN->Parse("body_content",CategoryGroup());
				$TPLN->Parse("admin_type","- Category Groups");
				break;
		}
		$db->close();
	}
	else
	{
		$TPLN->Parse("admin_type","");
		$TPLN->Parse("body_content","Select tasks from the leftside menu.");
	}

	$TPLN->Write();

	//------ Functions start ------------

	/**
	 * Parse the category group edit page and handle any input from the user.
	 */
	function CategoryGroup()
	{
		global $db;
		global $sql;
		$TPLN=new TPLN;
		$TPLN->Open("../private/templates/admin/categorygroup.tpl");

		if(isset($_REQUEST["whattodo"]))
		{
			if($_REQUEST["whattodo"]=="add")
			{
				$categoryGroup=new CategoryGroup;
				$categoryGroup->setId($db->getNextID(getSetting("seq_category_group")));
				$categoryGroup->setName($_REQUEST["name"]);
				$sql->addCategoryGroup($db,$categoryGroup);
			}
			else if($_REQUEST["whattodo"]=="edit")
			{
				$categoryGroup=new CategoryGroup;
				$categoryGroup->setId($_REQUEST["category_group"]);
				$categoryGroup->setName($_REQUEST["name"]);
				$sql->updateCategoryGroup($db,$categoryGroup);
			}
			else if($_REQUEST["whattodo"]=="del")
			{
				$sql->deleteCategoryGroup($_REQUEST["category_group"]);
			}
		}

		$result=$sql->getCategoryGroups($db);
		fillList($TPLN,$result,array("edit_category_group","del_category_group"));
		$result->free();

		return($TPLN->Output());
	}

	/**
	 * Parse the product group admin page and handle any user input.
	 */
	function ProductGroup()
	{
		global $db;
		global $sql;
		$TPLN=new TPLN;
		$TPLN->Open("../private/templates/admin/product_group.tpl");

		if(isset($_REQUEST["whattodo"]))
		{
			if($_REQUEST["whattodo"]=="add")
			{
				$pg=new ProductGroup;

				$pg->setId($db->getNextID(getSetting("seq_product_group")));
				$pg->setCategoryGroupId($_REQUEST["category_group"]);
				$pg->setName($_REQUEST["name"]);
				$pg->setPicWidth($_REQUEST["pic_width"]);
				$pg->setPicHeight($_REQUEST["pic_height"]);
				$pg->setSmallPicWidth($_REQUEST["pic_width_small"]);
				$pg->setSmallPicHeight($_REQUEST["pic_height_small"]);

				$sql->addProductGroup($db,$pg);
			}
			else if($_REQUEST["whattodo"]=="list")
			{
				$result=$sql->getProductGroupById($db,$_REQUEST["product_group"]);
				$row=$result->fetchRow();
				$TPLN->Parse("edit_product.pic_width",$row["pic_width"]);
				$TPLN->Parse("edit_product.pic_height",$row["pic_height"]);
				$TPLN->Parse("edit_product.pic_width_small",$row["pic_width_small"]);
				$TPLN->Parse("edit_product.pic_height_small",$row["pic_height_small"]);
				$TPLN->Parse("edit_product.name",$row["name"]);
				$TPLN->Parse("whattodo","edit");
				$result->free();
				
				$result=$sql->getProductGroups($db);
				while($row=$result->fetchRow())
				{
					$TPLN->Parse("edit_product_group.id",$row["id"]);
					$TPLN->Parse("edit_product_group.name",$row["name"]);
					if($row["id"]==$_REQUEST["product_group"])
						$TPLN->Parse("edit_product_group.selected","selected");
					else
						$TPLN->Parse("edit_product_group.selected","");
					$TPLN->Loop("edit_product_group");
				}
			}
			else if($_REQUEST["whattodo"]=="edit")
			{
				$pg=new ProductGroup;

				$pg->setId($_REQUEST["product_group"]);
				$pg->setCategoryGroupId($_REQUEST["category_group"]);
				$pg->setName($_REQUEST["name"]);
				$pg->setPicWidth($_REQUEST["pic_width"]);
				$pg->setPicHeight($_REQUEST["pic_height"]);
				$pg->setSmallPicWidth($_REQUEST["pic_width_small"]);
				$pg->setSmallPicHeight($_REQUEST["pic_height_small"]);

				$sql->updateProductGroup($db,$pg);
			}
			else if($_REQUEST["whattodo"]=="del")
			{
				$sql->deleteProductGroup($db,$_REQUEST["product_group"]);
			}
		}

		if((isset($_REQUEST["whattodo"])==false)||($_REQUEST["whattodo"]!="list"))
		{
			$TPLN->Parse("whattodo","list");
			$TPLN->EraseBloc("edit_product");			
		}

		$result=$sql->getCategoryGroups($db);
		fillList($TPLN,$result,array("add_category_group"));
		$result->free();

		$result=$sql->getProductGroups($db);
		if(!((isset($_REQUEST["whattodo"]))&&($_REQUEST["whattodo"]=="list")))
			fillList($TPLN,$result,array("edit_product_group","del_product_group"));
		else
			fillList($TPLN,$result,array("del_product_group"));
		$result->free();

		return($TPLN->Output());
	}

	/**
	 * Parse the region admin page and handle any user input.
	 */
	function Region()
	{
		global $db;
		global $sql;
		$TPLN=new TPLN;
		$TPLN->Open("../private/templates/admin/region.tpl");

		if(isset($_REQUEST["whattodo"]))
		{
			if($_REQUEST["whattodo"]=="add")
			{
				$region=new Region;
				$region->setId($db->getNextID(getSetting("seq_region")));
				$region->setName($_REQUEST["name"]);
				$sql->addRegion($db,$region);
			}
			else if($_REQUEST["whattodo"]=="edit")
			{
				$region=new Region;
				$region->setId($_REQUEST["id"]);
				$region->setName($_REQUEST["name"]);
				$sql->updateRegion($db,$region);
			}
			else if($_REQUEST["whattodo"]=="del")
			{
				$sql->deleteRegion($db,$_REQUEST["region"]);
			}
		}
		
		$result=$sql->getRegions($db);
		fillList($TPLN,$result,array("edit_region","del_region"));
		return($TPLN->Output());
	}
	
	/**
	 * Parse the category admin page and handle any user input.
	 */
	function Category()
	{
		global $db;
		global $sql;
		$TPLN=new TPLN;
		$TPLN->Open("../private/templates/admin/category.tpl");

		if(isset($_REQUEST["whattodo"]))
		{
			if($_REQUEST["whattodo"]=="add")
			{
				$category=new Category;
				$category->setId($db->getNextID(getSetting("seq_category")));
				$category->setCategoryGroupId($_REQUEST["category_group"]);
				$category->setName($_REQUEST["name"]);
				$sql->addCategory($db,$category);
			}
			else if($_REQUEST["whattodo"]=="list")
			{
				$result=$sql->getCategorysByCategoryGroupId($db,$_REQUEST["category_group"]);
				fillList($TPLN,$result,array("edit_category"));
				$TPLN->Parse("whattodo","edit");
				$result->free();
				
				$result=$sql->getCategoryGroups($db);
				while($row=$result->fetchRow())
				{
					$TPLN->Parse("edit_category_group.id",$row["id"]);
					$TPLN->Parse("edit_category_group.name",$row["name"]);
					if($row["id"]==$_REQUEST["category_group"])
						$TPLN->Parse("edit_category_group.selected","selected");
					else
						$TPLN->Parse("edit_category_group.selected","");
					$TPLN->Loop("edit_category_group");
				}
				$result->free();
			}
			else if($_REQUEST["whattodo"]=="edit")
			{
				$category=new Category;
				$category->setId($_REQUEST["category"]);
				$category->setCategoryGroupId($_REQUEST["category_group"]);
				$category->setName($_REQUEST["name"]);
				$sql->updateCategory($db,$category);
			}
			else if($_REQUEST["whattodo"]=="del")
			{
				$sql->deleteCategory($db,$_REQUEST["category"]);
			}
		}

		if((isset($_REQUEST["whattodo"])==false)||($_REQUEST["whattodo"]!="list"))
		{	
			$TPLN->Parse("whattodo","list");
			$TPLN->EraseBloc("edit_category_data");
		}

		$result=$sql->getCategoryGroups($db);
		if(!((isset($_REQUEST["whattodo"]))&&($_REQUEST["whattodo"]=="list")))
			fillList($TPLN,$result,array("edit_category_group","add_category_group"));
		else
			fillList($TPLN,$result,array("add_category_group"));

		$result=$sql->getCategorys($db);
		while($row=$result->fetchRow())
		{
			$TPLN->Parse("del_category.id",$row["id"]);
			$TPLN->Parse("del_category.name",$row["category_group_name"] . " - " . $row["name"]);
			$TPLN->Loop("del_category");
		}
		$result->free();
		return($TPLN->Output());
	}
	
	/**
	 * Parse the add item admin page and handle any user input.
	 */
	function AddItem()
	{
		global $db;
		global $sql;
		$TPLN=new TPLN;
		$TPLN->Open("../private/templates/admin/additem.tpl");

		if(isset($_REQUEST["whattodo"]))
		{
			if($_REQUEST["whattodo"]=="add")
			{
				$id=$db->getNextId(getSetting("seq_item"));

				$pictureUrl=null;
				$smallPictureUrl=null;
				if($_FILES["picture"]["error"]==0)
				{
					$filename=str_replace("\\","/",getSetting("upload_path")) . "/" . $id;// . ".jpg";
					saveImage($filename,$sql->getImageSize($db,$_REQUEST["product_group"]));
					$pictureUrl=PathToURL($filename . ".jpg");
					if(strpos($pictureUrl,"/")!=0)
						$pictureUrl="/" . $pictureUrl;
					$smallPictureUrl=PathToURL($filename . "_small.jpg");
					if(strpos($smallPictureUrl,"/")!=0)
						$smallPictureUrl="/" . $smallPictureUrl;
				}

				$item=new Item;
				$item->setId($id);
				$item->setName($_REQUEST["name"]);
				$item->setDescription(htmlspecialchars(nl2br($_REQUEST["description"])));
				$item->setRRP($_REQUEST["rrp"]);
				$item->setPrice($_REQUEST['price']);
				$item->setProductGroupId($_REQUEST["product_group"]);
				$item->setCategoryId($_REQUEST["category"]);
				$item->setRegionId($_REQUEST["region"]);
				$item->setPicture($pictureUrl);
				$item->setSmallPicture($smallPictureUrl);
				$item->setReleaseDate($_REQUEST["release_date"]);

				$sql->addItem($db,$item);

				$TPLN->Parse("whattodo","list");
				$TPLN->EraseBloc("data");
			}
			else if($_REQUEST["whattodo"]=="list")
			{
				$result=$sql->getProductGroups($db);
				while($row=$result->fetchRow())
				{
					$TPLN->Parse("add_product_group.id",$row["id"]);
					$TPLN->Parse("add_product_group.name",$row["name"]);
					if($row["id"]==$_REQUEST["product_group"])
						$TPLN->Parse("add_product_group.selected","selected");
					else
						$TPLN->Parse("add_product_group.selected","");
					$TPLN->Loop("add_product_group");
				}

				$result=$sql->getCategorysByProductGroupId($db,$_REQUEST["product_group"]);
				fillList($TPLN,$result,array("data.add_category"));
				$result->free();
				$TPLN->Parse("whattodo","add");
			}
		}
		else
		{
			$TPLN->Parse("whattodo","list");
			$TPLN->EraseBloc("data");
		}

		if(!((isset($_REQUEST["whattodo"]))&&($_REQUEST["whattodo"]=="list")))
		{
			$result=$sql->getProductGroups($db);
			fillList($TPLN,$result,array("add_product_group"));
			$result->free();
		}
		else if((isset($_REQUEST["whattodo"]))&&($_REQUEST["whattodo"]=="list"))
		{
			$result=$sql->getRegions($db);
			fillList($TPLN,$result,array("add_region"));
			$result->free();
			$TPLN->Parse("data.rrp_currency",getSetting("currency"));
			$TPLN->Parse("data.price_currency",getSetting("currency"));
		}
		return($TPLN->Output());
	}
	
	function EditItem()
	{
	}

	function fillList($TPLN, $result,$blocks)
	{
		if($result->numRows()>0)
		{
			while($row=$result->fetchRow())
			{
				foreach($blocks as $blockName)
				{
					$TPLN->Parse($blockName . ".id",$row["id"]);
					$TPLN->Parse($blockName . ".name",$row["name"]);
					$TPLN->Loop($blockName);					
				}
			}
		}
		else
		{
			foreach($blocks as $blockName)
			{
				$TPLN->EraseBloc($blockName);
			}
		}
	}

	function saveImage($filename, $size)
	{
		resizeAndSave($_FILES["picture"]["tmp_name"],$filename . ".jpg",$size);
		resizeAndSave($_FILES["picture"]["tmp_name"],$filename . "_small.jpg",array("width"=>$size["small_width"],"height"=>$size["small_height"]));
		unlink($_FILES["picture"]["tmp_name"]);
	}
?>
