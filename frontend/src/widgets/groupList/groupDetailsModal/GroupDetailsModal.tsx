import {Modal, Input, Typography} from 'antd'
import {useState} from 'react'
import {SubjectListForGroup} from './SubjectListForGroup'
import {UserListForGroup} from './UserListForGroup'

type Group = {
	id: string,
	name: string,
}

type GroupDetailsModalProps = {
	group: Group | null,
	visible: boolean,
	onSave: (group: Group) => void,
	onClose: () => void,
}

export const GroupDetailsModal = ({
	group,
	visible,
	onSave,
	onClose,
}: GroupDetailsModalProps) => {
	const [name, setName] = useState(group?.name || '')

	const handleSave = () => {
		onSave({
			id: group?.id || String(Date.now()),
			name,
		})
	}

	return (
		<Modal
			title={group ? 'Редактировать группу' : 'Создать группу'}
			visible={visible}
			onOk={handleSave}
			onCancel={onClose}
			width={800}
		>
			<div>
				<Typography.Title level={4}>Название группы</Typography.Title>
				<Input
					value={name}
					onChange={e => setName(e.target.value)}
					placeholder="Введите название группы"
				/>
			</div>
			<UserListForGroup />
			<SubjectListForGroup />
		</Modal>
	)
}
