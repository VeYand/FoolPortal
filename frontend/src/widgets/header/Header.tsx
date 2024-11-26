import {UserOutlined} from '@ant-design/icons'
import {Layout, Avatar, Typography} from 'antd'
import {userEntitySlice} from 'entities/user'
import {useNavigate} from 'react-router-dom'
import {useLazyLogout} from 'shared/libs/query'
import {useAppDispatch, useAppSelector} from 'shared/redux'
import {LoginRoute, ProfileRoute, UserPortalRoute} from 'shared/routes'
import {Popover} from 'shared/ui/Popover/Popover'
import styles from './Header.module.css'

const Header = () => {
	const navigate = useNavigate()
	const dispatch = useAppDispatch()
	const [logout] = useLazyLogout()
	const {user} = useAppSelector(state => state.userEntity)

	const onClickToLogo = () => {
		navigate(UserPortalRoute.path)
	}

	const navigateToPortal = () => {
		navigate(UserPortalRoute.path)
	}

	const navigateToProfile = () => {
		navigate(ProfileRoute.path)
	}

	const handleLogout = async () => {
		dispatch(userEntitySlice.actions.setInitialized(false))
		await logout({})
		navigate(LoginRoute.path)
	}

	return (
		<Layout.Header className={styles.header}>
			<Typography.Title level={1} className={styles.title} onClick={onClickToLogo}>
				{'Fool Portal'}
			</Typography.Title>
			<Popover
				items={[
					{label: 'Перейти в портал пользователя', onClick: navigateToPortal},
					{label: 'Перейти в мой профиль', onClick: navigateToProfile},
					{label: 'Выйти', onClick: handleLogout},
				]}
			>
				<Avatar
					src={user.imageSrc}
					icon={<UserOutlined />}
				/>
			</Popover>
		</Layout.Header>
	)
}

export {
	Header,
}