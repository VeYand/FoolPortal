import {UserOutlined} from '@ant-design/icons'
import {Layout, Avatar, Typography} from 'antd'
import styles from './Header.module.css'

const Header = () => (
	<Layout.Header className={styles.header}>
		<Typography.Title level={1} className={styles.title}>
			{'Fool Portal'}
		</Typography.Title>
		<Avatar icon={<UserOutlined />} />
	</Layout.Header>
)

export {
	Header,
}