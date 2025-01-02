import {BookOutlined, CalendarOutlined, HomeOutlined, TeamOutlined, UserOutlined} from '@ant-design/icons'
import {useMemo} from 'react'
import {USER_ROLE} from 'shared/types'
import {GroupList} from 'widgets/groupList/GroupList'
import {GroupMembers} from 'widgets/groupMembers/GroupMembers'
import {LocationsList} from 'widgets/locationList/locationsList'
import {Schedule} from 'widgets/shedule/Schedule'
import {SubjectsList} from 'widgets/subjectList/subjectsList'
import {UserList} from 'widgets/userList/UserList'

const useTabs = (currentUserRole: USER_ROLE) => useMemo(() => ([
	{
		key: '1',
		name: 'Расписание',
		icon: <CalendarOutlined />,
		content: <Schedule/>,
	},
	...(currentUserRole === USER_ROLE.ADMIN || currentUserRole === USER_ROLE.OWNER ? [{
		key: '2',
		name: 'Пользователи',
		icon: <UserOutlined />,
		content: <UserList />,
	},
	{
		key: '3',
		name: 'Группы',
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
	}] : []),
	...(currentUserRole === USER_ROLE.STUDENT ? [{
		key: '6',
		name: 'Мои одногруппники',
		icon: <TeamOutlined />,
		content: <GroupMembers />,
	}] : []),

]), [currentUserRole])

export {
	useTabs,
}
