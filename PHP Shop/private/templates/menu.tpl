<div id="leftnav">
	<div class="floatright">
		<b>Search</b>
		<form action="search.php" method="get" name="search">
			<table cellspacing="0">
				<tr>
					<td colspan="2" align="left">
						<input type="text" name="search_term" size="19"/>
					</td>
				</tr>
				<tr>
					<td align="left">
						<select name="product">
							<option value="-1">All Platforms</option>
							<bloc::product_list2>
								<option value="{id}">{name}</option>
							</bloc::product_list2>
						</select>
					</td>
					<td align="right">
						<a href="javascript:document.search.submit();"><img src="images/icons/search.png"/></a>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div class="floatright">
		<b>Select platform</b><br/>
		<bloc::product_list>
			<a href="{url}">{name}</a><br/>
		</bloc::product_list>
	</div>
	<bloc::categorys>
		<div class="floatright">
			<a href="productlist.php?pgid={pgid}&fid={fid}">All</a><br/>
			<bloc::category>
				<a href="productlist.php?pgid={pgid}&fid={fid}&cid={cid}">{name}</a><br/>
			</bloc::category>
		</div>
	</bloc::categorys>
</div>