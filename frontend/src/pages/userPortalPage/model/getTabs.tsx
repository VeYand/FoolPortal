import {BookOutlined, CalendarOutlined, HomeOutlined, TeamOutlined, UserOutlined} from '@ant-design/icons'

const getTabs = () => ([
	{key: '1', name: 'Schedule', icon: <CalendarOutlined />, content: <div>{'Schedule'}</div>},
	{key: '2', name: 'Users', icon: <UserOutlined />, content: <div>{'Users'}</div>},
	{key: '3', name: 'Groups', icon: <TeamOutlined />, content: <div>{'Groups'}</div>},
	{key: '4', name: 'Locations', icon: <HomeOutlined />, content: <div>{'Locations'}</div>},
	{key: '5', name: 'Lessons', icon: <BookOutlined />, content: <div>{'Lessons'}</div>},
])

export {
	getTabs,
}