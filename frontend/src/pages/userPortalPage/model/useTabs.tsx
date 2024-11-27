import {BookOutlined, CalendarOutlined, HomeOutlined, TeamOutlined, UserOutlined} from '@ant-design/icons'
import {useMemo} from 'react'
import {GroupList} from 'widgets/groupList/GroupList'
import {LocationsList} from 'widgets/locationList/locationsList'
import {SubjectsList} from 'widgets/subjectList/subjectsList'

const useTabs = () => useMemo(() => ([
	{
		key: '1',
		name: 'Schedule',
		icon: <CalendarOutlined />,
		content: <div>{'Schedule'}</div>,
	},
	{
		key: '2',
		name: 'Users',
		icon: <UserOutlined />,
		content: <div>{'Users'}</div>,
	},
	{
		key: '3',
		name: 'Groups',
		icon: <TeamOutlined />,
		content: <GroupList/>,
	},
	{
		key: '4',
		name: 'Локации',
		icon: <HomeOutlined />,
		content: <LocationsList />,
	},
	{
		key: '5',
		name: 'Предметы',
		icon: <BookOutlined />,
		content: <SubjectsList />,
	},
]), [])

export {
	useTabs,
}
