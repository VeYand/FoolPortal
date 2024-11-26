import {BookOutlined, CalendarOutlined, HomeOutlined, TeamOutlined, UserOutlined} from '@ant-design/icons'
import {SubjectList} from 'widgets/subjectList/subjectList'

const getTabs = () => ([
	{key: '1', name: 'Schedule', icon: <CalendarOutlined />, content: <div>{'Schedule'}</div>},
	{key: '2', name: 'Users', icon: <UserOutlined />, content: <div>{'Users'}</div>},
	{key: '3', name: 'Groups', icon: <TeamOutlined />, content: <div>{'Groups'}</div>},
	{key: '4', name: 'Locations', icon: <HomeOutlined />, content: <div>{'Locations'}</div>},
	{key: '5', name: 'Предметы', icon: <BookOutlined />, content: <SubjectList/>},
])

export {
	getTabs,
}