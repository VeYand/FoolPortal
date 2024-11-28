import {useEffect, useState} from 'react'
import {CourseData, GroupData, UserData, UsersList} from 'shared/api'
import {getViewableUserName} from 'shared/libs'
import {
	useLazyListCourses,
	useLazyListGroups,
	useLazyListSubjects,
	useLazyListTeacherSubjects,
	useLazyListUsers,
} from 'shared/libs/query'
import {remapApiUsersToUsersList} from 'shared/libs/remmapers/remapApiUserToUserData'
import {USER_ROLE} from 'shared/types'
import {Group} from '../groupDetailsModal/GroupDetailsModal'
import {Student} from '../groupDetailsModal/StudentListForGroup'
import {Subject, Teacher, TeacherSubject} from '../groupDetailsModal/SubjectListForGroup'

const retrieveStudentsAndTeachersFromUsersResponse = (response?: UsersList): [Student[], Teacher[]] => {
	if (!response) {
		return [[], []]
	}

	const users = remapApiUsersToUsersList(response.users)

	const students = users.filter(user => user.role === USER_ROLE.STUDENT).map(student => ({
		id: student.userId,
		name: getViewableUserName(student),
	}))

	const teachers = users.filter(user => user.role === USER_ROLE.TEACHER).map(teacher => ({
		id: teacher.userId,
		name: getViewableUserName(teacher),
	}))

	return [students, teachers]
}

const findUserIdsByGroup = (users: UserData[], groupId: string): string[] => (
	users.filter(user => user.groupIds.includes(groupId)).map(user => user.userId)
)

const findTeacherSubjectIdsByGroup = (courses: CourseData[], groupId: string): string[] => (
	[...courses.filter(course => course.groupId === groupId).map(course => course.teacherSubjectId)]
)

const retrieveGroupsList = (groups: GroupData[], users: UserData[], courses: CourseData[]): Group[] => (
	groups.map((group): Group => ({
		id: group.groupId,
		name: group.name,
		studentIds: findUserIdsByGroup(users, group.groupId),
		teacherSubjectIds: findTeacherSubjectIdsByGroup(courses, group.groupId),
	}))
)

// const findGroupById = (groups: Group[], groupId: string): Group | undefined => {
// 	for (const group of groups) {
// 		if (group.id === groupId) {
// 			return group
// 		}
// 	}
// 	return undefined
// }

type AvailableData = {
	availableStudents: Student[],
	availableTeachers: Teacher[],
	availableSubjects: Subject[],
	availableTeacherSubjects: TeacherSubject[],
	initialGroups: Group[],
}

type UseInitializeReturns = {
	loading: boolean,
	data?: AvailableData,
}

const useInitialize = (): UseInitializeReturns => {
	const [listUsers] = useLazyListUsers()
	const [listSubjects] = useLazyListSubjects()
	const [listTeacherSubjects] = useLazyListTeacherSubjects()
	const [listGroups] = useLazyListGroups()
	const [listCourses] = useLazyListCourses()
	const [loading, setLoading] = useState(true)

	const [data, setData] = useState<AvailableData | undefined>()

	// const saveGroup = (group: Group) => {
	// 	const existensGroup = findGroupById(data?.initialGroups ?? [], group.id)
	//
	// 	if (existensGroup) {
	//
	// 	}
	// }

	useEffect(() => {
		const fetchData = async () => {
			try {
				setLoading(true)
				const [
					usersResponse,
					subjectsResponse,
					teacherSubjectsResponse,
					groupsResponse,
					coursesResponse,
				] = await Promise.all([
					listUsers({}),
					listSubjects({}),
					listTeacherSubjects({}),
					listGroups({}),
					listCourses({}),
				])

				const [availableStudents, availableTeachers] = retrieveStudentsAndTeachersFromUsersResponse(usersResponse.data)

				const availableSubjects = subjectsResponse.data?.subjects.map(subject => ({
					id: subject.subjectId,
					name: subject.name,
				})) ?? []

				const availableTeacherSubjects = teacherSubjectsResponse.data?.teacherSubjects.map(ts => ({
					teacherSubjectId: ts.teacherSubjectId,
					subjectId: ts.subjectId,
					teacherId: ts.teacherId,
				})) ?? []

				const initialGroups = retrieveGroupsList(
					groupsResponse.data?.groups ?? [],
					usersResponse.data?.users ?? [],
					coursesResponse.data?.courses ?? [],
				)

				setData({
					availableStudents,
					availableTeachers,
					availableSubjects,
					availableTeacherSubjects,
					initialGroups,
				})
				setLoading(false)
			}
			catch (error) {
				console.error('Error fetching data:', error)
			}
		}

		fetchData()
	}, [listCourses, listGroups, listSubjects, listTeacherSubjects, listUsers])

	return {loading, data}
}

export {
	useInitialize,
}