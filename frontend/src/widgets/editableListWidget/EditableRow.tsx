import {Button, Input} from 'antd'
import {useEffect, useState} from 'react'
import styles from './EditableRow.module.css'

type EditableItem = {
	id: string,
	name: string,
}

type EditableRowProps = {
	item: EditableItem,
	onSave: (name: string, id: string | undefined) => void,
	onDelete: (id: string) => void,
}

const EditableRow = ({item, onSave, onDelete}: EditableRowProps) => {
	const [isEditing, setIsEditing] = useState(false)
	const [value, setValue] = useState(item.name)

	const handleEdit = () => setIsEditing(true)
	const handleSave = () => {
		onSave(value, item.id)
		setIsEditing(false)
	}
	const handleDelete = () => {
		onDelete(item.id)
	}

	useEffect(() => {
		setValue(item.name)
	}, [isEditing, item.name])

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
						{'Сохранить'}
					</Button>
				) : (
					<div className={styles.buttons}>
						<Button onClick={handleEdit}>{'Редактировать'}</Button>
						<Button onClick={handleDelete} danger>{'Удалить'}</Button>
					</div>
				)}
			</td>
		</tr>
	)
}

export {
	EditableRow,
	type EditableItem,
}
