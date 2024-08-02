<form action="admin/index.php" enctype="multipart/form-data" method="post" name="add">
	<input type="hidden" name="id" value="4"/>
	<input type="hidden" name="whattodo" value="{whattodo}"/>
	<table>
		<tr>
			<td width="135">
				Product Group:
			</td>
			<td>
				<select name="product_group">
					<bloc::add_product_group><option value="{id}" {selected}>{name}</option></bloc::add_product_group>
				</select>
				<input type="button" value="Ok" onclick="document.add.whattodo.value='list';document.add.submit();"/>
			</td>
		</tr>
		<bloc::data>
			<tr>
				<td width="135">
					Name:
				</td>
				<td>
					<input type="text" name="name"/>
				</td>
			</tr>
			<tr>
				<td width="135" valign="top">
					Description:
				</td>
				<td>
					<textarea name="description" cols="40" rows="10"></textarea>
				</td>
			</tr>
			<tr>
				<td width="135">
					Recommended Retail Pricing ({rrp_currency}):
				</td>
				<td>
					<input type="text" name="rrp"/>
				</td>
			</tr>
			<tr>
				<td width="135">
					Our Price ({price_currency}):
				</td>
				<td>
					<input type="text" name="price"/>
				</td>
			</tr>
			<tr>
				<td width="135">
					Release date:
				</td>
				<td>
					<input type="text" name="release_date"/>
				</td>
			</tr>
			<tr>
				<td>
					Category:
				</td>
				<td>
					<select name="category">
						<bloc::add_category><option value="{id}">{name}</option></bloc::add_category>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Region:
				</td>
				<td>
					<select name="region">
						<bloc::add_region><option value="{id}">{name}</option></bloc::add_region>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Picture:
				</td>
				<td>
					<input type="file" name="picture"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" value="Add"/>
				</td>
			</tr>
		</bloc::data>
	</table>
</form>