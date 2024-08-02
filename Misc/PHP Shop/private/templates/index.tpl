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
				<bloc::product_group>
					<div class="contentfloat">
						<b><center><a href="productlist.php?pgid={pgid}&fid={fid}">{name}</a></center></b><br/>
						<bloc::product>
							<div class="productInfo">
								<a href="productinfo.php?pid={id}">
								<img src="{picture}" alt="{name}"/><br/>
								{name2}<br/>
								{price}</a>
							</div>
						</bloc::product>
					</div>
				</bloc::product_group>
			</div>
		</div>
		<p>
			&nbsp;
		</p>
	</body>
</html>