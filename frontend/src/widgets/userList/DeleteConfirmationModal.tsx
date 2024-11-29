import {Modal, Button, Space, Typography} from 'antd'
import {getViewableUserName} from 'shared/libs'
import {UserData} from 'shared/types'

const {Text} = Typography

type DeleteConfirmationModalProps = {
	user?: UserData,
	onClose: () => void,
	onConfirm: (user: UserData) => void,
}

const DeleteConfirmationModal = ({
	user,
	onClose,
	onConfirm,
}: DeleteConfirmationModalProps) => {
	if (!user) {
		return <></>
	}

	return (
		<Modal
			title="Подтверждение удаления"
			visible={true}
			onCancel={onClose}
			footer={null}
		>
			<Text>
				{'Вы уверены, что хотите удалить пользователя '}<Text strong>{getViewableUserName(user)}</Text>{'?'}
			</Text>
			<Space style={{marginTop: 16}}>
				<Button onClick={onClose}>{'Отмена'}</Button>
				<Button
					type="primary"
					danger
					onClick={() => {
						onConfirm(user)
						onClose()
					}}
				>
					{'Удалить'}
				</Button>
			</Space>
		</Modal>
	)
}

export {
	DeleteConfirmationModal,
}