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
				<div class="contentfloat">
					<div class="product_info_picture">
						<img src="{picture}" alt="{pic_name}"/>
					</div>
					<div class="product_info_list">
						<b>Name:</b> {name}<br/>
						<b>Product code:</b> {product_code}<br/>
						<b>Format:</b> {format}<br/>
						<b>Release date:</b> {release_date}<br/>
						<b>Region:</b> {region}<br/>
						<b>RRP:</b> <span class="rrp">{rrp}</span><br/>
						<b>Our Price:</b> {price}<br/>
						<b>Currency Converter:</b> {currency}<br/><br/>
						<a href="cart.php?add=true&pid={cart_pid}"><img src="images/icons/addtocart.png"/></a><br/><br/>
						<a href="wishlist.php?add=true&pid={wishlist_pid}"><img src="images/icons/addtowishlist.png"/></a>
					</div>
				</div>
				<div class="contentfloat">
					<div class="product_description">
						<b>Description:</b> {description}
					</div>
				</div>
			</div>
		</div>
		<p>
			&nbsp;
		</p>
	</body>
</html>