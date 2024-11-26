import {UserOutlined} from '@ant-design/icons'
import {Layout, Avatar, Typography} from 'antd'
import {userEntitySlice} from 'entities/user'
import {useLocation, useNavigate} from 'react-router-dom'
import {useLazyLogout} from 'shared/libs/query'
import {useAppDispatch, useAppSelector} from 'shared/redux'
import {LoginRoute, ProfileRoute, UserPortalRoute} from 'shared/routes'
import {Popover, PopoverItem} from 'shared/ui/Popover/Popover'
import styles from './Header.module.css'

const usePopoverItems = (): PopoverItem[] => {
	const dispatch = useAppDispatch()
	const navigate = useNavigate()
	const location = useLocation()
	const [logout] = useLazyLogout()

	const handleNavigation = (path: string) => () => navigate(path)

	const handleLogout = async () => {
		dispatch(userEntitySlice.actions.setInitialized(false))
		await logout({})
		navigate(LoginRoute.path)
	}

	const popoverItems: PopoverItem[] = []

	if (location.pathname !== UserPortalRoute.path) {
		popoverItems.push({
			label: 'Перейти в портал пользователя',
			onClick: handleNavigation(UserPortalRoute.path),
		})
	}

	if (location.pathname !== ProfileRoute.path) {
		popoverItems.push({
			label: 'Перейти в мой профиль',
			onClick: handleNavigation(ProfileRoute.path),
		})
	}

	popoverItems.push({
		label: 'Выйти',
		onClick: handleLogout,
	})

	return popoverItems
}

const Header = () => {
	const navigate = useNavigate()
	const {user} = useAppSelector(state => state.userEntity)
	const popoverItems = usePopoverItems()

	const onClickToLogo = () => {
		navigate(UserPortalRoute.path)
	}

	return (
		<Layout.Header className={styles.header}>
			<Typography.Title level={1} className={styles.title} onClick={onClickToLogo}>
				{'Student Portal'}
			</Typography.Title>
			<Popover
				items={popoverItems}
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