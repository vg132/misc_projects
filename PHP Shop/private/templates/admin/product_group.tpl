<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="1"/>
	<input type="hidden" name="whattodo" value="add"/>
	<table>
		<tr>
			<td width="135">
				Name:
			</td>
			<td>
				<input type="text" name="name"/>
			</td>
		</tr>
		<tr>
			<td>
				Category group:
			</td>
			<td>
				<select name="category_group">
					<bloc::add_category_group><option value="{id}">{name}</option></bloc::add_category_group>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Default image size
			</td>
			<td>
				<table>
					<tr>
						<td>w:</td>
						<td><input type="text" name="pic_width" value="0" size="3"/></td>
					</tr>
					<tr>
						<td>h:</td>
						<td><input type="text" name="pic_height" value="0" size="3"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				Default small image size
			</td>
			<td>
				<table>
					<tr>
						<td>w:</td>
						<td><input type="text" name="pic_width_small" value="0" size="3"/></td>
					</tr>
					<tr>
						<td>h:</td>
						<td><input type="text" name="pic_height_small" value="0" size="3"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Add"/>
			</td>
		</tr>
	</table>
</form>
<hr/>
<form action="admin/index.php" method="post" name="edit">
	<input type="hidden" name="id" value="1"/>
	<input type="hidden" name="whattodo" value="{whattodo}"/>
	<table>
		<tr>
			<td width="135">
				Edit product group:
			</td>
			<td>
				<select name="product_group">
					<bloc::edit_product_group><option value="{id}" {selected}>{name}</option></bloc::edit_product_group>
				</select>
			</td>
			<td>
				<input type="button" value="Ok" onclick="document.edit.whattodo.value='list';document.edit.submit();"/>
			</td>
		</tr>
		<bloc::edit_product>
			<tr>
				<td>
					New name:
				</td>
				<td>
					<input type="text" name="name" value="{name}"/>
				</td>
			</tr>
			<tr>
				<td>
					Default image size
				</td>
				<td>
					<table>
						<tr>
							<td>w:</td>
							<td><input type="text" name="pic_width" value="{pic_width}" size="3"/></td>
						</tr>
						<tr>
							<td>h:</td>
							<td><input type="text" name="pic_height" value="{pic_height}" size="3"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					Default image size
				</td>
				<td>
					<table>
						<tr>
							<td>w:</td>
							<td><input type="text" name="pic_width_small" value="{pic_width_small}" size="3"/></td>
						</tr>
						<tr>
							<td>h:</td>
							<td><input type="text" name="pic_height_small" value="{pic_height_small}" size="3"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" value="Save"/>
				</td>
			</tr>
		</bloc::edit_product>
	</table>
</form>
<hr/>
<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="1"/>
	<input type="hidden" name="whattodo" value="del"/>
	<table>
		<tr>
			<td width="135">
				Delete product group:
			</td>
			<td>
				<select name="product_group">
					<bloc::del_product_group><option value="{id}">{name}</option></bloc::del_product_group>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Delete"/>
			</td>
		</tr>
	</table>
</form>