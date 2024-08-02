<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="6"/>
	<input type="hidden" name="whattodo" value="add"/>
	<table>
		<tr>
			<td width="135">
				Add category group:
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
<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="6"/>
	<input type="hidden" name="whattodo" value="edit"/>
	<table>
		<tr>
			<td width="135">
				Edit category group:
			</td>
			<td>
				<select name="category_group">
					<bloc::edit_category_group><option value="{id}">{name}</option></bloc::edit_category_group>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				New name:
			</td>
			<td>
				<input type="text" name="name"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Save"/>
			</td>
		</tr>
	</table>
</form>
<hr/>
<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="6"/>
	<input type="hidden" name="whattodo" value="del"/>
	<table>
		<tr>
			<td width="135">
				Delete category group:
			</td>
			<td>
				<select name="category_group">
					<bloc::del_category_group><option value="{id}">{name}</option></bloc::del_category_group>
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