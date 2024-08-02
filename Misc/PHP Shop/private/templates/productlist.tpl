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
				<div class="product_list_container">
					<bloc::product>
						<div class="product_list_row">
							<div class="product_list_left">
								<a href="productinfo.php?pid={id}">{name}</a><br/>
								<b>Release date:</b> {release_date}<br/>
								<b>Our Price:</b> {price}
							</div>
							<div class="product_list_right">
								<a href="cart.php?add=true&pid={cart_pid}"><img src="images/icons/addtocart.png"/></a><br/>
								<bloc::list>
									<a href="wishlist.php?add=true&pid={wishlist_pid}"><img src="images/icons/addtowishlist.png"/></a>
								</bloc::list>
								<bloc::wishlist>
									<a href="wishlist.php?remove=true&pid={remove_pid}"><img src="images/icons/remove.png"/></a>
								</bloc::wishlist>
							</div>
						</div>
					</bloc::product>
					<bloc::error>
						<b>{message}</b>
					</bloc::error>
				</div>
			</div>
		</div>
		<p>
				&nbsp;
		</p>
	</body>
</html>