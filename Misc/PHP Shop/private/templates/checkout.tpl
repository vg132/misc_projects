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
								{quantity}
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
				</table>
			</div>
			<div id="content">
				<b>Shipping Address</b><br/><br/>
				<b>Name:</b> {name}<br/>
				<b>Address:</b> {address}<br/>
				<b>City:</b> {city}<br/>
				<b>Post code:</b> {post_code}<br/>
				<b>State:</b> {state}<br/>
				<b>Country:</b> {country}
				<p>
					<a href="order.php">Send Order</a>
				</p>
			</div>
		</div>
		<p>
			&nbsp;
		</p>
	</body>
</html>