import {useCallback, useEffect, useState} from 'react'
import {
	CourseData,
	GroupData,
	UserData,
	UsersList,
} from 'shared/api'
import {getViewableUserName} from 'shared/libs'
import {
	useLazyCreateGroupMembers,
	useLazyCreateCourses,
	useLazyCreateGroup,
	useLazyDeleteCourses,
	useLazyDeleteGroup,
	useLazyListCourses,
	useLazyListGroups,
	useLazyListSubjects,
	useLazyListTeacherSubjects,
	useLazyListUsers,
	useLazyDeleteGroupMembers,
	useLazyUpdateGroup,
} from 'shared/libs/query'
import {remapApiUsersToUsersList} from 'shared/libs/remmapers/remapApiUserToUserData'
import {USER_ROLE} from 'shared/types'
import {Group} from '../groupDetailsModal/GroupDetailsModal'
import {Student} from '../groupDetailsModal/StudentListForGroup'
import {Subject, Teacher, TeacherSubject} from '../groupDetailsModal/SubjectListForGroup'

const mapUsersToStudentsAndTeachers = (response?: UsersList): [Student[], Teacher[]] => {
	if (!response) {
		return [[], []]
	}

	const users = remapApiUsersToUsersList(response.users)
	const students = users
		.filter(user => user.role === USER_ROLE.STUDENT)
		.map(student => ({
			id: student.userId,
			name: getViewableUserName(student),
		}))

	const teachers = users
		.filter(user => user.role === USER_ROLE.TEACHER)
		.map(teacher => ({
			id: teacher.userId,
			name: getViewableUserName(teacher),
		}))

	return [students, teachers]
}

const getUserIdsByGroup = (users: UserData[], groupId: string): string[] =>
	users
		.filter(user => user.groupIds.includes(groupId))
		.map(user => user.userId)

const getTeacherSubjectIdsByGroup = (courses: CourseData[], groupId: string): string[] =>
	courses
		.filter(course => course.groupId === groupId)
		.map(course => course.teacherSubjectId)

const mapApiDataToGroups = (groups: GroupData[], users: UserData[], courses: CourseData[]): Group[] =>
	groups.map(group => ({
		id: group.groupId,
		name: group.name,
		studentIds: getUserIdsByGroup(users, group.groupId),
		teacherSubjectIds: getTeacherSubjectIdsByGroup(courses, group.groupId),
	}))

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
	saveGroup: (group: Group) => void,
	deleteGroup: (groupId: string) => void,
}

export const useInitialize = (): UseInitializeReturns => {
	const [createGroup] = useLazyCreateGroup()
	const [deleteGroup] = useLazyDeleteGroup()
	const [updateGroup] = useLazyUpdateGroup()
	const [createCourses] = useLazyCreateCourses()
	const [deleteCourses] = useLazyDeleteCourses()
	const [createGroupMembers] = useLazyCreateGroupMembers()
	const [deleteGroupMembers] = useLazyDeleteGroupMembers()
	const [listUsers] = useLazyListUsers()
	const [listSubjects] = useLazyListSubjects()
	const [listTeacherSubjects] = useLazyListTeacherSubjects()
	const [listGroups] = useLazyListGroups()
	const [listCourses] = useLazyListCourses()

	const [loading, setLoading] = useState(true)
	const [data, setData] = useState<AvailableData | undefined>()
	const [courses, setCourses] = useState<CourseData[]>([])

	const handleDeleteGroup = async (groupId: string) => {
		try {
			if (data) {
				await deleteGroup({groupId})
				setData(prev =>
					(prev
						? {...prev, initialGroups: prev.initialGroups.filter(group => group.id !== groupId)}
						: undefined),
				)
			}
		}
		catch (error) {
			console.error(`Error deleting group: ${error}`)
		}
	}

	const handleSaveGroup = async (group: Group) => {
		try {
			const existingGroup = data?.initialGroups.find(g => g.id === group.id)
			let groupId = existingGroup ? group.id : ''

			if (!existingGroup) {
				const response = await createGroup({name: group.name})
				if (response.isSuccess && response.data) {
					groupId = response.data.groupId
				}
			}

			if (existingGroup && existingGroup.name !== group.name) {
				await updateGroup({groupId, name: group.name})
			}

			const tsToAdd = group.teacherSubjectIds.filter(id => !existingGroup?.teacherSubjectIds.includes(id))
			const tsToRemove = existingGroup?.teacherSubjectIds.filter(id => !group.teacherSubjectIds.includes(id)) || []
			if (tsToAdd.length) {
				await createCourses({courses: tsToAdd.map(id => ({teacherSubjectId: id, groupId}))})
			}
			if (tsToRemove.length) {
				const courseIdsToRemove = courses
					.filter(course => course.groupId === groupId && tsToRemove.includes(course.teacherSubjectId))
					.map(course => course.courseId)
				await deleteCourses({courseIds: courseIdsToRemove})
			}

			const studentsToAdd = group.studentIds.filter(id => !existingGroup?.studentIds.includes(id))
			const studentsToRemove
				= existingGroup?.studentIds.filter(id => !group.studentIds.includes(id)) || []
			if (studentsToAdd.length) {
				await createGroupMembers({groupIds: [groupId], userIds: studentsToAdd})
			}
			if (studentsToRemove.length) {
				await deleteGroupMembers({groupIds: [groupId], userIds: studentsToRemove})
			}

			await fetchData()
		}
		catch (error) {
			console.error(`Error saving group: ${error}`)
		}
	}

	const fetchData = useCallback(async () => {
		try {
			setLoading(true)
			const [usersResponse, subjectsResponse, teacherSubjectsResponse, groupsResponse, coursesResponse]
				= await Promise.all([
					listUsers({}),
					listSubjects({}),
					listTeacherSubjects({}),
					listGroups({}),
					listCourses({}),
				])

			const [availableStudents, availableTeachers] = mapUsersToStudentsAndTeachers(usersResponse.data)
			const availableSubjects
				= subjectsResponse.data?.subjects.map(subject => ({
					id: subject.subjectId,
					name: subject.name,
				})) || []
			const availableTeacherSubjects
				= teacherSubjectsResponse.data?.teacherSubjects.map(ts => ({
					teacherSubjectId: ts.teacherSubjectId,
					subjectId: ts.subjectId,
					teacherId: ts.teacherId,
				})) || []
			const initialGroups = mapApiDataToGroups(
				groupsResponse.data?.groups || [],
				usersResponse.data?.users || [],
				coursesResponse.data?.courses || [],
			)

			setCourses(coursesResponse.data?.courses || [])
			setData({availableStudents, availableTeachers, availableSubjects, availableTeacherSubjects, initialGroups})
		}
		catch (error) {
			console.error(`Error fetching data: ${error}`)
		}
		finally {
			setLoading(false)
		}
	}, [listUsers, listSubjects, listTeacherSubjects, listGroups, listCourses])

	useEffect(() => {
		fetchData()
	}, [fetchData])

	return {
		loading,
		data,
		saveGroup: handleSaveGroup,
		deleteGroup: handleDeleteGroup,
	}
}
