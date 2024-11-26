import {UserOutlined} from '@ant-design/icons'
import {Layout, Avatar, Typography} from 'antd'
import {useNavigate} from 'react-router-dom'
import {UserPortalRoute} from 'shared/routes'
import styles from './Header.module.css'

const Header = () => {
	const navigate = useNavigate()

	const onClickToLogo = () => {
		navigate(UserPortalRoute.path)
	}

	return (
		<Layout.Header className={styles.header}>
			<Typography.Title level={1} className={styles.title} onClick={onClickToLogo}>
				{'Fool Portal'}
			</Typography.Title>
			<Avatar icon={<UserOutlined/>}/>
		</Layout.Header>
	)
}

export {
	Header,
}