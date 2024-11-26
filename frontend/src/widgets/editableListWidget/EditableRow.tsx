import {Button, Input} from 'antd'
import {useState} from 'react'
import styles from './EditableRow.module.css'

type EditableItem = {
	id: string,
	name: string,
}

type EditableRowProps = {
	item: EditableItem,
	onSave: (name: string, id: string | undefined) => void,
}

const EditableRow = ({item, onSave}: EditableRowProps) => {
	const [isEditing, setIsEditing] = useState(false)
	const [value, setValue] = useState(item.name)

	const handleEdit = () => setIsEditing(true)
	const handleSave = () => {
		onSave(value, item.id)
		setIsEditing(false)
	}

	return (
		<tr className={styles.row}>
			<td className={styles.nameCell}>
				{isEditing ? (
					<Input
						className={styles.input}
						value={value}
						onChange={e => setValue(e.target.value)}
					/>
				) : (
					<span>{item.name}</span>
				)}
			</td>
			<td className={styles.actionCell}>
				{isEditing ? (
					<Button onClick={handleSave} type="primary">
						Save
					</Button>
				) : (
					<Button onClick={handleEdit}>Edit</Button>
				)}
			</td>
		</tr>
	)
}

export {
	EditableRow,
	type EditableItem,
}