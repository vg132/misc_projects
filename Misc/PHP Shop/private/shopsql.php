<?php
	require_once("database.php");

	class ShopSQL
	{
		function getImageSize($db, $product_id)
		{
			$result=$db->executeQuery("SELECT pic_width,pic_height,pic_width_small,pic_height_small FROM product_group WHERE id=" . $product_id);
			if($row=$result->fetchRow())
			{
				$size=array("width"=>$row["pic_width"],
										"height"=>$row["pic_height"],
										"small_width"=>$row["pic_width_small"],
										"small_height"=>$row["pic_height_small"]);
			}
			return($size);
		}

		/* Category Group */

		public function addCategoryGroup($db,$categoryGroup)
		{
			$statement=$db->prepareStatement("INSERT INTO category_group(id,name) VALUES(?,?)");
			$db->executeStatement($statement,$categoryGroup->getData());
		}

		public function updateCategoryGroup($db,$categoryGroup)
		{
			$statement=$db->prepareStatement("UPDATE category_group SET id=?, name=? WHERE id=?");
			$data=$categoryGroup->getData();
			array_push($data,$categoryGroup->getId());
			$db->executeStatement($statement,$data);
		}

		public function deleteCategoryGroup($db,$id)
		{
			$statement=$db->prepareStatement("DELETE FROM category_group WHERE id=?");
			$db->executeStatement($statement,$id);
		}
		
		public function getCategoryGroups($db)
		{
			return($db->executeQuery("SELECT id, name FROM category_group"));
		}
		
		public function getCategoryGroupById($db, $id)
		{
			$statment=$db->prepareStatement("SELECT id, name FROM category_group WHERE id=?");
			return($db->executeStatement($statement,$id));
		}

		/* Category */

		public function addCategory($db,$category)
		{
			$statement=$db->prepareStatement("INSERT INTO category(id,category_group_id,name) VALUES(?,?,?)");
			$db->executeStatement($statement,$category->getData());
		}

		public function updateCategory($db,$category)
		{
			$statement=$db->prepareStatement("UPDATE category SET id=?, category_group_id=?, name=? WHERE id=?");
			$data=$category->getData();
			array_push($data,$category->getId());
			$db->executeStatement($statement,$data);
		}

		public function deleteCategory($db,$id)
		{
			$statement=$db->prepareStatement("DELETE FROM category WHERE id=?");
			$db->executeStatement($statement,$id);
		}
		
		public function getCategorys($db)
		{
			return($db->executeQuery("SELECT c.id, c.name, cg.name AS category_group_name FROM category c, category_group cg WHERE c.category_group_id=cg.id ORDER BY c.name ASC"));
		}
		
		public function getCategorysByCategoryGroupId($db, $categoryGroupId)
		{
			$statement=$db->prepareStatement("SELECT id, name FROM category WHERE category_group_id=? ORDER BY name ASC");
			return($db->executeStatement($statement,$categoryGroupId));
		}

		public function getCategorysByProductGroupId($db, $productGroup)
		{
			$statement=$db->prepareStatement("SELECT c.name, c.id FROM category c, product_group pg WHERE pg.id=? AND pg.category_group_id=c.category_group_id ORDER BY c.name ASC");
			return($db->executeStatement($statement,$productGroup));
		}

		public function getCategoryById($db, $id)
		{
			$statment=$db->prepareStatement("SELECT id, name, category_group FROM category WHERE id=?");
			return($db->executeStatement($statement,$id));
		}
		
		/* Product Group */

		public function addProductGroup($db,$productGroup)
		{
			$statement=$db->prepareStatement("INSERT INTO product_group(id, category_group_id, name, pic_width, pic_height, pic_width_small, pic_height_small) VALUES(?,?,?,?,?,?,?)");
			$db->executeStatement($statement,$productGroup->getData());
		}

		public function updateProductGroup($db,$productGroup)
		{
			$statement=$db->prepareStatement("UPDATE product_group SET id=?, category_group_id=?, name=?, pic_width=?, pic_height=?, pic_width_small=?, pic_height_small=? WHERE id=?");
			$data=$productGroup->getData();
			array_push($data,$productGroup->getId());
			$db->executeStatement($statement,$data);
		}

		public function deleteProductGroup($db,$id)
		{
			$statement=$db->prepareStatement("DELETE FROM product_group WHERE id=?");
			$db->executeStatement($statement,$id);
		}

		public function getProductGroups($db)
		{
			return($db->executeQuery("SELECT id, name, category_group_id, pic_width, pic_height, pic_width_small, pic_height_small FROM product_group ORDER BY name ASC"));
		}

		public function getProductGroupById($db, $id)
		{
			$statement=$db->prepareStatement("SELECT id, name, category_group_id, pic_width, pic_height, pic_width_small, pic_height_small FROM product_group WHERE id=?");
			return($db->executeStatement($statement,$id));
		}

		/* Region */

		/**
		 * Adds a new region to the database.
		 * 
		 * param $region a object of the type region with information about the region
		 * that will be added.
		 */
		public function addRegion($db,$region)
		{
			$statement=$db->prepareStatement("INSERT INTO region(id,name) VALUES(?,?)");
			$db->executeStatement($statement,$region->getData());
		}

		/**
		 * Changes name for a region.
		 * 
		 * param $region a object of the type Region with information about the region that
		 * is about to change.
		 */
		public function updateRegion($db,$region)
		{
			$statement=$db->prepareStatement("UPDATE region SET id=?, name=? WHERE id=?");
			$data=$region->getData();
			array_push($data,$region->getId());
			$db->executeStatement($statement,$data);
		}

		/**
		 * Removes the specified region.
		 * 
		 * param $id id for the region to remove.
		 */
		public function deleteRegion($db,$id)
		{
			$statement=$db->prepareStatement("DELETE FROM region WHERE id=?");
			$db->executeStatement($statement,$id);
		}

		/**
		 * Returns all regions (DVD, Video games and other stuff) from the database
		 */
		public function getRegions($db)
		{
			return($db->executeQuery("SELECT id, name FROM region ORDER BY name ASC"));
		}
		
		/**
		 * Returns the specified region.
		 * 
		 * param $id id for the region to get
		 */
		public function getRegionById($db, $id)
		{
			$statment=$db->prepareStatement("SELECT id, name FROM region WHERE id=?");
			return($db->executeStatement($statement,$id));
		}

		/* Item */

		/**
		 * Adds a new item to the database.
		 */
		public function addItem($db, $item)
		{
			$statement=$db->prepareStatement("INSERT INTO item(id,name,description,rrp,product_group_id,category_id,region_id,picture,small_picture,release_date) VALUES(?,?,?,?,?,?,?,?,?,?)");
			$db->executeStatement($statement,$item->getData());
			$this->addPrice($db,$item->getId(),$item->getPrice());
		}

		/**
		 * Adds a new price for a item
		 */
		public function addPrice($db,$itemId,$price)
		{
			$statement=$db->prepareStatement("INSERT INTO item_price(id,item_id,price_date,price) VALUES(?,?,?,?)");
			$data=array($db->getNextID(getSetting("seq_item_price")),
									$itemId,
									date('YmdHis', time()),
									$price);
			$db->executeStatement($statement,$data);
		}

		/**
		 * Updates a item in the database
		 */
		public function updateItem($db,$item)
		{
			$statement=$db->prepareStatement("UPDATE item SET id=?,name=?,description=?,rrp=?,product_group_id=?,category_id=?,region_id=?,picture=?,small_picture=?,release_date=? WHERE id=?");
			$data=$item->getData();
			array_push($data,$item->getId());
			$db->executeStatement($statement,$data);			
		}

		/**
		 * Deletes the specified item from the database.
		 */
		public function deleteItem($db,$id)
		{
			$statement=$db->prepareStatement("DELETE FROM item WHERE id=?");
			$db->executeStatement($statement,$id);
		}

		/**
		 * Finds all products that has the specified term in the title or description.
		 * 
		 * Returns: a result set containing all found items. 
		 */
		public function findItem($db, $term)
		{
			$term="%" . $term . "%";
			$statement=$db->prepareStatement("SELECT i.id, c.name as category, pg.name as product, r.name as region, i.rrp, i.name, i.description, i.picture, i.small_picture, i.release_date, ip.price FROM item i, category c, product_group pg, region r, item_price ip WHERE i.category_id=c.id AND i.product_group_id=pg.id AND i.region_id=r.id AND (i.name LIKE ? OR i.description LIKE ?) AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=i.id GROUP BY item_id))");
			return($db->executeStatement($statement,array($term,$term)));
		}

		/**
		 * Finds all products that has the specified term in the title or description
		 * and is part of the specified product group.
		 * 
		 * Returns: a result set containing all found items. 
		 */
		public function findItemByProductGroup($db,$term,$productGroupId)
		{
			$term="%" . $term . "%";
			$statement=$db->prepareStatement("SELECT i.id, c.name as category, pg.name as product, r.name as region, i.rrp, i.name, i.description, i.picture, i.small_picture, i.release_date, ip.price FROM item i, category c, product_group pg, region r, item_price ip WHERE i.category_id=c.id AND i.product_group_id=pg.id AND i.region_id=r.id AND (i.name LIKE ? OR i.description LIKE ?) AND i.product_group_id=? AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=i.id GROUP BY item_id)) ORDER BY c.name");
			return($db->executeStatement($statement,array($term,$term,$productGroupId)));
		}

		/**
		 * Finds all products that has the specified term in the title or description
		 * and is part of the specified product group.
		 * 
		 * Returns: a result set containing all found items. 
		 */
		public function getNewestItemByProductGroup($db,$productGroupId)
		{
			$statement=$db->prepareStatement("SELECT i.id, c.name as category, pg.name as product, r.name as region, i.rrp, i.name, i.description, i.picture, i.small_picture, i.release_date, ip.price FROM item i, category c, product_group pg, region r, item_price ip WHERE i.category_id=c.id AND i.product_group_id=pg.id AND i.region_id=r.id AND i.product_group_id=? AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=i.id GROUP BY item_id)) ORDER BY rand()");
			return($db->executeStatement($statement,array($productGroupId)));
		}

		/**
		 * Gets the item with the specified id.
		 * 
		 * Returns: a result set containing the item.
		 */
		public function getItemById($db,$id)
		{
			$statement=$db->prepareStatement("SELECT i.id, c.name as category, pg.name as product, r.name as region, i.rrp, i.name, i.description, i.picture, i.small_picture, i.release_date, ip.price FROM item i, category c, product_group pg, region r, item_price ip WHERE i.category_id=c.id AND i.product_group_id=pg.id AND i.region_id=r.id AND i.id=? AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=? GROUP BY item_id))");
			return($db->executeStatement($statement,array($id,$id)));
		}

		/**
		 * Gets the item with the specified id.
		 * 
		 * Returns: a result set containing the item.
		 */
		public function getItemByProductGroupId($db,$productGroupId)
		{
			$statement=$db->prepareStatement("SELECT i.id, c.name as category, pg.name as product, r.name as region, i.rrp, i.name, i.description, i.picture, i.small_picture, i.release_date, ip.price FROM item i, category c, product_group pg, region r, item_price ip WHERE i.category_id=c.id AND i.product_group_id=pg.id AND i.region_id=r.id AND pg.id=? AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=i.id GROUP BY item_id))");
			return($db->executeStatement($statement,$productGroupId));
		}		

		public function getItemByProductGroupAndCategory($db,$productGroupId,$categoryId)
		{
			$statement=$db->prepareStatement("SELECT i.id, c.name as category, pg.name as product, r.name as region, i.rrp, i.name, i.description, i.picture, i.small_picture, i.release_date, ip.price FROM item i, category c, product_group pg, region r, item_price ip WHERE i.category_id=c.id AND i.product_group_id=pg.id AND i.region_id=r.id AND pg.id=? AND c.id=? AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=i.id GROUP BY item_id))");
			return($db->executeStatement($statement,array($productGroupId,$categoryId)));
		}

		/**
		 * Gets the item with the specified id and id's to referenced tables.
		 * 
		 * Returns: a result set containing the item.
		 */
		public function getRawItemById($db, $id)
		{
			$statement=$db->prepareStatement("SELECT id,category_id,product_group_id,region_id,rrp,name,description,picture,small_picture,release_date FROM item WHERE id=?");
			return($db->executeStatement($statement,$id));
		}

		/* Customer */

		/**
		 * Adds a new customer to the database.
		 * 
		 * @param database $db a database connection.
		 * @param Customer $customer a customer object.
		 */
		public function addCustomer($db,$customer)
		{
			$statement=$db->prepareStatement("INSERT INTO customer(id,email,password,name,address,city,state,post_code,customer_type,country_id,currency) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
			return($db->executeStatement($statement,$customer->getData()));
		}

		/**
		 * Checks if a customer exists.
		 * 
		 * @param database $db a database connection.
		 * @param string $email a email address.
		 * 
		 * @return boolean true if a user with that email address exist, otherwise false.
		 */
		public function customerExist($db,$email)
		{
			$statement=$db->prepareStatement("SELECT id FROM customer WHERE email=?");
			$result=$db->executeStatement($statement,array($email));
			if($result->numRows()>0)
				return(true);
			else
				return(false);
		}

		/**
		 * Finds and returns a customer if he is found.
		 * 
		 * @param database $db a database connection.
		 * @param string $email a email address.
		 */
		public function findCustomerByEmail($db,$email)
		{
			$statement=$db->prepareStatement("SELECT cust.id, cust.email, cust.name,cust.address,cust.city,cust.state,cust.post_code,cust.customer_type,c.name AS country, cust.currency FROM customer cust, country c WHERE c.id=cust.country_id AND cust.email=?");
			return($db->executeStatement($statement,$email));
		}

		public function autoLogin($db,$email,$password)
		{
			$statement=$db->prepareStatement("SELECT email, password FROM customer WHERE email=?");
			$result=$db->executeStatement($statement,$email);
			if(($row=$result->fetchRow()))
			{
				if(md5($row["email"] . $row["password"])==$password)
					return(true);
				else
					return(false);
			}
			else
			{
				return(false);
			}
		}

		/**
		 * Returns all customer information if the login process is successfull.
		 * 
		 * param $email the email address used to login
		 * param $password a md5 hash of the password
		 */
		public function login($db,$email,$password)
		{
			$statement=$db->prepareStatement("SELECT cust.id, cust.email, cust.name,cust.address,cust.city,cust.state,cust.post_code,cust.customer_type,c.name AS country, cust.currency FROM customer cust, country c WHERE c.id=cust.country_id AND cust.email=? AND cust.password=?");
			return($db->executeStatement($statement,array($email,$password)));
		}

		/**
		 * Returns the password hash that is saved in the cookie on the clients computer
		 * to auto login on the next visit.
		 */
		public function getCookiePassword($db,$email)
		{
			$statement=$db->prepareStatement("SELECT email, password FROM customer WHERE email=?");
			$result=$db->executeStatement($statement,array($email));
			if($row=$result->fetchRow())
			{
				return(md5($row["email"] . $row["password"]));
			}
			return(null);
		}

		public function updateCustomer($db,$customer)
		{
			$statement=$db->prepareStatement("UPDATE customer SET name=?, address=?, city=?, state=?,post_code=?, country_id=?, currency=? WHERE id=?");
			return($db->executeStatement($statement,array($customer->getName(),$customer->getAddress(),$customer->getCity(),$customer->getState(),$customer->getPostCode(),$customer->getCountryId(),$customer->getCurrency(),$customer->getId())));
		}

		/* Country */

		/**
		 * Returns all items (countrys) from the country table
		 */
		public function getCountryList($db)
		{
			return($db->executeQuery("SELECT id, name FROM country ORDER BY name ASC"));
		}

		/**
		 * Gets the name and id of the country with the specified id.
		 */		
		public function getCountry($db, $id)
		{
			$statement=$db->prepareStatement("SELECT id, name FROM country WHERE id=?");
			return($db->executeStatement($statement,$id));
		}

		/* Wish list */

		/**
		 * Adds a new item to the users wish list
		 */
		public function addWishlistItem($db,$customerId,$itemId)
		{
			$statement=$db->prepareStatement("INSERT INTO wishlist(id,customer_id,item_id) VALUES(?,?,?)");
			$id=$db->getNextID(getSetting("seq_wishlist"));
			return($db->executeStatement($statement,array($id,$customerId,$itemId)));
		}

		/**
		 * Removes a item from the wish list
		 */
		public function removeFromWishlist($db,$id)
		{
			$statement=$db->prepareStatement("DELETE FROM wishlist WHERE id=?");
			return($db->executeStatement($statement,$id));
		}

		/**
		 * Returns all items on the spicified users wish list
		 */
		public function getWishlist($db,$customerId)
		{
			$statement=$db->prepareStatement("SELECT w.id, i.name, w.item_id, ip.price, i.release_date FROM wishlist w, item i, item_price ip WHERE w.item_id=i.id AND ip.id=i.id AND w.customer_id=? AND ip.id=(SELECT id FROM item_price WHERE price_date=(SELECT max(price_date) FROM item_price WHERE item_id=i.id GROUP BY item_id))");
			return($db->executeStatement($statement,$customerId));
		}
		
		/**  Orders **/
		public function addInvoice($db,$invoice)
		{
			$statement=$db->prepareStatement("INSERT INTO invoice(id,customer_id,order_date,total_price) VALUES(?,?,?,?)");
			$invoice->setId($db->getNextID(getSetting("seq_invoice")));
			return($db->executeStatement($statement,$invoice->getData()));
		}
		
		public function addInvoiceItem($db,$item)
		{
			$statement=$db->prepareStatement("INSERT INTO invoice_item(id,invoice_id,item_id,quantity,price) VALUES(?,?,?,?,?)");
			$item->setId($db->getNextID(getSetting("seq_invoice_item")));
			return($db->executeStatement($statement,$item->getData()));
		}
		
		public function getInvoices($db,$customer)
		{
			$statement=$db->prepareStatement("SELECT id, customer_id, order_date, total_price FROM invoice WHERE customer_id=?");
			return($db->executeStatement($statement,$customer));
		}
		
		public function getInvoiceItems($db,$invoice)
		{
			$statement=$db->prepareStatement("SELECT ii.id, ii.quantity, ii.price, i.name FROM invoice_item ii, item i WHERE ii.item_id=i.id AND invoice_id=?");
			return($db->executeStatement($statement,$invoice));
		}
	}
?>