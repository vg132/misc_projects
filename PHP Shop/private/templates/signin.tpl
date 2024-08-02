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
					<b>Signin</b>
					<bloc::error>
						<div class="error">
							<p>
								{error_message}
							</p>
						</div>
					</bloc::error>
					<form name="signin" action="signin.php" method="post">
						<input type="hidden" name="signin" value="true"/>
						<div class="row">
							<span class="label">Email:</span>
							<span class="formw"><input type="text" size="25" name="email" value="{email}" /></span>
						</div>
						<div class="row">
							<span class="label">Password:</span>
							<span class="formw"><input type="password" size="25" name="password" /></span>
						</div>
						<div class="row">
							<span class="label">Save login:</span>
							<span class="formw"><input type="checkbox" name="savelogin"/></span>
						</div>
						<div class="row">
							<span class="label">&nbsp;</span>
							<span class="formw">
								<a href="javascript:document.signin.submit();"><img src="images/icons/search.png" alt="login"/></a>
							</span>
						</div>
						<div class="row">
							Don't have an account? <a href="register.php">Click here</a>.<br/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
