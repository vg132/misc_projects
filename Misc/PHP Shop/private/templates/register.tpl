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
				<div class="form">
					<b>Register</b>
					<bloc::error>
						<div class="error">
							<p>
								{error_message}
							</p>
						</div>
					</bloc::error>
					<form name="register" action="register.php" method="post">
						<input type="hidden" name="register" value="true"/>
						<div class="row">
							<span class="label">Email:</span>
							<span class="formw"><input type="text" size="25" name="email" value="{email}" /></span>
						</div>
						<div class="row">
							<span class="label">Password:</span>
							<span class="formw"><input type="password" size="25" name="password" /></span>
						</div>
						<div class="row">
							<span class="label">Name:</span>
							<span class="formw"><input type="text" size="25" name="name" value="{name}" /></span>
						</div>
						<div class="row">
							<span class="label">Address:</span>
							<span class="formw"><input type="text" size="25" name="address" value="{address}" /></span>
						</div>
						<div class="row">
							<span class="label">City:</span>
							<span class="formw"><input type="text" size="25" name="city" value="{city}" /></span>
						</div>
						<div class="row">
							<span class="label">State:</span>
							<span class="formw"><input type="text" size="25" name="state" value="{state}" /></span>
						</div>
						<div class="row">
							<span class="label">Post code:</span>
							<span class="formw"><input type="text" size="25" name="post_code" value="{post_code}" /></span>
						</div>
						<div class="row">
							<span class="label">Country:</span>
							<span class="formw">
								<select name="country">
									<bloc::country>
										<option value="{id}" {selected}>{name}</option>
									</bloc::country>
								</select>
							</span>
						</div>
						<div class="row">
							<span class="label">Currency:</span>
							<span class="formw">
								<select name="currency">
									<bloc::currency>
										<option value="{currency}" {selected}>{currency}</option>
									</bloc::currency>
								</select>
							</span>
						</div>
						<div class="row">
							<span class="label">&nbsp;</span>
							<span class="formw">
								<a href="javascript:document.register.submit();"><img src="images/icons/search.png" alt="login"/></a>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>