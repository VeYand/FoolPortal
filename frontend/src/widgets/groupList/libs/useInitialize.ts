import {useCallback, useEffect, useState} from 'react'
import {CourseData, GroupData, UserData, UsersList} from 'shared/api'
import {getViewableUserName} from 'shared/libs'
import {
	useLazyAddStudentsToGroup,
	useLazyCreateCourses,
	useLazyCreateGroup, useLazyDeleteCourses, useLazyDeleteGroup,
	useLazyListCourses,
	useLazyListGroups,
	useLazyListSubjects,
	useLazyListTeacherSubjects,
	useLazyListUsers, useLazyRemoveStudentsFromGroup, useLazyUpdateGroup,
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

const findGroupById = (groups: Group[], groupId: string): Group | undefined => {
	for (const group of groups) {
		if (group.id === groupId) {
			return group
		}
	}
	return undefined
}

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

const useInitialize = (): UseInitializeReturns => {
	const [createGroup] = useLazyCreateGroup()
	const [deleteGroup] = useLazyDeleteGroup()
	const [updateGroup] = useLazyUpdateGroup()
	const [createCourses] = useLazyCreateCourses()
	const [deleteCourses] = useLazyDeleteCourses()
	const [addStudentsToGroup] = useLazyAddStudentsToGroup()
	const [removeStudentsFromGroup] = useLazyRemoveStudentsFromGroup()
	const [listUsers] = useLazyListUsers()
	const [listSubjects] = useLazyListSubjects()
	const [listTeacherSubjects] = useLazyListTeacherSubjects()
	const [listGroups] = useLazyListGroups()
	const [listCourses] = useLazyListCourses()
	const [loading, setLoading] = useState(true)

	const [data, setData] = useState<AvailableData | undefined>()
	const [courses, setCourses] = useState<CourseData[]>([])

	const deleteGroupHandler = async (groupId: string) => {
		if (data) {
			await deleteGroup({groupId})
			setData({
				...data,
				initialGroups: data.initialGroups.filter(group => group.id !== groupId) ?? [],
			})
		}
	}

	const saveGroup = async (group: Group) => {
		const existGroup = findGroupById(data?.initialGroups ?? [], group.id)

		let newName: string | undefined
		let groupId = ''
		const tsToAdd: string[] = []
		const tsToRemove: string[] = []
		const studToAdd: string[] = []
		const studToRemove: string[] = []

		const shouldRefetchUsers = false
		const shouldRefetchSubjects = false
		const shouldRefetchTeacherSubjects = false
		const shouldRefetchGroups = false
		const shouldRefetchCourses = false

		if (!existGroup) {
			const response = await createGroup({name: group.name})
			if (response.isSuccess && response.data) {
				groupId = response.data.groupId
			}
		}
		if (!groupId) {
			return
		}
		if (existGroup) {
			if (existGroup.name !== group.name) {
				newName = group.name
			}

			const tsOld = existGroup.teacherSubjectIds
			const tsUpdated = group.teacherSubjectIds


			for (const tsU of tsUpdated) {
				if (!tsOld.includes(tsU)) {
					tsToAdd.push(tsU)
				}
			}

			for (const tsO of tsOld) {
				if (!tsUpdated.includes(tsO)) {
					tsToRemove.push(tsO)
				}
			}

			const stdudOld = existGroup.studentIds
			const stUpdated = group.studentIds


			for (const stU of stUpdated) {
				if (!stdudOld.includes(stU)) {
					studToAdd.push(stU)
				}
			}

			for (const stO of stdudOld) {
				if (!stUpdated.includes(stO)) {
					studToRemove.push(stO)
				}
			}
		}

		let changed = false
		if (newName !== undefined) {
			changed = true
			updateGroup({groupId, name: newName})
		}

		if (tsToAdd.length) {
			changed = true
			createCourses({courses: tsToAdd.map(ts => ({
				teacherSubjectId: ts,
				groupId,
			}))})
		}
		if (tsToRemove.length) {
			const courseIdsToRemove: string[] = []

			for (const course of courses) {
				if (course.groupId === groupId && tsToRemove.includes(course.teacherSubjectId)) {
					courseIdsToRemove.push(course.courseId)
				}
			}

			changed = true
			deleteCourses({courseIds: courseIdsToRemove})
		}
		if (studToAdd.length) {
			changed = true
			addStudentsToGroup({groupId, studentIds: studToAdd})
		}
		if (studToRemove.length) {
			changed = true
			removeStudentsFromGroup({groupId, studentIds: studToAdd})
		}
		if (changed) {
			fetchData(
				shouldRefetchUsers,
				shouldRefetchSubjects,
				shouldRefetchTeacherSubjects,
				shouldRefetchGroups,
				shouldRefetchCourses,
			)
		}
	}

	const fetchData = useCallback(async(
		shouldRefetchUsers = true,
		shouldRefetchSubjects = true,
		shouldRefetchTeacherSubjects = true,
		shouldRefetchGroups = true,
		shouldRefetchCourses = true,
	) => {
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
			setCourses(coursesResponse.data?.courses ?? [])
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
	}, [listCourses, listGroups, listSubjects, listTeacherSubjects, listUsers])

	useEffect(() => {
		fetchData()
	}, [fetchData])

	return {loading, data, saveGroup, deleteGroup: deleteGroupHandler}
}

export {
	useInitialize,
}