import {UserOutlined} from '@ant-design/icons'
import {Avatar, Card, Space, Typography} from 'antd'
import {useAppSelector} from 'shared/redux'
import {RoleTag} from '../../shared/ui/RoleTag/RoleTag'
import {useViewableData} from './libs/useViewableData'

const UserCardWidget = () => {
	const {user} = useAppSelector(state => state.userEntity)
	const viewableData = useViewableData(user)

	return (
		<Card>
			<Space direction="vertical" size="large" style={{width: '100%'}}>
				<div style={{textAlign: 'center'}}>
					<Avatar
						size={100}
						src={user.imageSrc}
						icon={<UserOutlined />}
					/>
				</div>

				<div style={{textAlign: 'center'}}>
					<Typography.Title level={3}>
						{viewableData.name}
					</Typography.Title>
					<RoleTag
						role={user.role}
					/>
				</div>

				<div>
					<Typography.Text strong>Email:</Typography.Text> <Typography.Text>{user.email}</Typography.Text>
				</div>

				<div>
					<Typography.Text strong>{viewableData.groupNamesTitle}</Typography.Text>{' '}
					<Typography.Text>{viewableData.groupNames}</Typography.Text>
				</div>
			</Space>
		</Card>
	)
}

export {
	UserCardWidget,
}