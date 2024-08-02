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
				<form action="cart.php" method="post" name="cart">
					<input type="hidden" name="update" value="true"/>
					<table width="90%">
						<tr class="odd">
							<th align="left">
								Title
							</th>
							<th align="left" width="50">
								Quantity
							</th>
							<th align="left">
								Cost
							</th>
							<th align="left">
								Total
							</th>
						</tr>
						<bloc::item>
							<tr class="{class}">
								<td>
									{name}
								</td>
								<td width="50">
									<input type="text" name="quantity_{q_id}" value="{quantity}" size="3"/>
								</td>
								<td>
									{price}
								</td>
								<td>
									{total_price}
								</td>
							</tr>
						</bloc::item>
						<bloc::total>
							<tr class="{class}">
								<td colspan="3" align="right">
									<b>Total price:</b>
								</td>
								<td>
									<b>{total_price}</b>
								</td>
							</tr>
						</bloc::total>
						<bloc::currency>
							<tr class="{class}">
								<td colspan="3">
								</td>
								<td>
									{currency_price}
								</td>
							</tr>
						</bloc::currency>
						<tr>
							<td colspan="4" align="center">
								<center><a href="index.php"><img src="images/icons/continueshopping.png"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:document.cart.submit();"><img src="images/icons/updatecart.png"></a>&nbsp;&nbsp;&nbsp;<a href="checkout.php"><img src="images/icons/checkout.png"></a></center>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<p>
			&nbsp;
		</p>
	</body>
</html>