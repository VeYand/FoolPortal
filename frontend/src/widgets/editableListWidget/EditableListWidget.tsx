import {Table, Typography, Input, Button} from 'antd'
import {useState} from 'react'
import styles from './EditableListWidget.module.css'
import {EditableItem, EditableRow} from './EditableRow'

type EditableListWidgetProps = {
	title: string,
	data: EditableItem[],
	onSave: (name: string, id?: string) => void,
	onDelete: (id: string) => void,
}

const EditableListWidget = ({title, data, onSave, onDelete}: EditableListWidgetProps) => {
	const [newItemName, setNewItemName] = useState('')

	const handleAddItem = () => {
		if (newItemName.trim()) {
			onSave(newItemName)
			setNewItemName('')
		}
	}

	const columns = [
		{
			title: 'Name',
			dataIndex: 'name',
			key: 'name',
			render: (_: any, item: EditableItem) => <EditableRow item={item} onSave={onSave} onDelete={onDelete} />,
		},
		{
			title: 'Action',
			dataIndex: 'action',
			key: 'action',
		},
	]

	return (
		<div className={styles.widget}>
			<Typography.Title level={4}>{title}</Typography.Title>
			<div className={styles.newItemContainer}>
				<Input
					placeholder="Введите новое название"
					value={newItemName}
					onChange={e => setNewItemName(e.target.value)}
					className={styles.newItemInput}
				/>
				<Button onClick={handleAddItem} type="primary" className={styles.addButton}>
					{'Добавить'}
				</Button>
			</div>
			<Table
				dataSource={data}
				columns={columns}
				rowKey="id"
				pagination={false}
				showHeader={false}
				className={styles.table}
				locale={{emptyText: 'Нет данных'}}
			/>
		</div>
	)
}

export {
	EditableListWidget,
}