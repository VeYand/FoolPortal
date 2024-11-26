import {useState} from 'react'
import {EditableListWidget} from 'widgets/editableListWidget/EditableListWidget'
import {EditableItem} from 'widgets/editableListWidget/EditableRow'

const LessonList = () => {
	const [items, setItems] = useState<EditableItem[]>([
		{id: '1', name: 'Предмет 1'},
		{id: '2', name: 'Предмет 2'},
	])

	const handleSave = (newName: string, id?: string) => {
		if (id) {
			setItems(prevItems =>
				prevItems.map(item => (item.id === id ? {...item, name: newName} : item)),
			)
		}
		else {
			const newItem: EditableItem = {
				id: Math.random().toString(),
				name: newName,
			}
			setItems(prevItems => [...prevItems, newItem])
		}
	}

	return (
		<div>
			<EditableListWidget title="Предметы" data={items} onSave={handleSave} />
		</div>
	)
}

export {
	LessonList,
}
