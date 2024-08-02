<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="3"/>
	<input type="hidden" name="whattodo" value="add"/>
	<table>
		<tr>
			<td width="135">
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
				Category name:
			</td>
			<td>
				<input type="text" name="name"/>
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
	<input type="hidden" name="id" value="3"/>
	<input type="hidden" name="whattodo" value="{whattodo}"/>
	<table>
		<tr>
			<td width="135">
				Category group:
			</td>
			<td>
				<select name="category_group">
					<bloc::edit_category_group><option value="{id}" {selected}>{name}</option></bloc::edit_category_group>
				</select>
			</td>
			<td>
				<input type="button" value="Ok" onclick="document.edit.whattodo.value='list';document.edit.submit();"/>
			</td>
		</tr>
		<bloc::edit_category_data>
			<tr>
				<td>
					Category:
				</td>
				<td colspan="2">
					<select name="category">
						<bloc::edit_category><option value="{id}">{name}</option></bloc::edit_category>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					New name:
				</td>
				<td colspan="2">
					<input type="text" name="name"/>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="right">
					<input type="submit" value="Save"/>
				</td>
			</tr>
		</bloc::edit_category_data>
	</table>
</form>
<hr/>
<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="3"/>
	<input type="hidden" name="whattodo" value="del"/>
	<table>
		<tr>
			<td width="135">
				Delete category:
			</td>
			<td>
				<select name="category">
					<bloc::del_category><option value="{id}">{name}</option></bloc::del_category>
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