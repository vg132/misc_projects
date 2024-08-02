<form action="admin/index.php" method="post">
	<input type="hidden" name="id" value="2"/>
	<input type="hidden" name="whattodo" value="add"/>
	<table>
		<tr>
			<td width="135">
				Add region:
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
	<input type="hidden" name="id" value="2"/>
	<input type="hidden" name="whattodo" value="edit"/>
	<table>
		<tr>
			<td width="135">
				Edit region:
			</td>
			<td>
				<select name="region">
					<bloc::edit_region><option value="{id}">{name}</option></bloc::edit_region>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				New name
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
	<input type="hidden" name="id" value="2"/>
	<input type="hidden" name="whattodo" value="del"/>
	<table>
		<tr>
			<td width="135">
				Delete region:
			</td>
			<td>
				<select name="region">
					<bloc::del_region><option value="{id}">{name}</option></bloc::del_region>
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