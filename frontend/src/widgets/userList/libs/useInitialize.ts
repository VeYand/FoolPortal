import {message} from 'antd'
import {useEffect, useState, useCallback} from 'react'
import {
	useLazyListUsers,
	useLazyListSubjects,
	useLazyListGroups,
	useLazyListTeacherSubjects,
	useLazyCreateTeacherSubjects,
	useLazyDeleteTeacherSubjects,
	useLazyDeleteUser,
	useLazyCreateUser,
	useLazyUpdateUser,
	useLazyCreateGroupMembers,
	useLazyDeleteGroupMembers,
} from 'shared/libs/query'
import {remapUserRoleToApiUserRole} from 'shared/libs/remmapers'
import {remapApiUsersToUsersList} from 'shared/libs/remmapers/remapApiUserToUserData'
import {UserData, GroupData, SubjectData, TeacherSubjectData} from 'shared/types'

type UseInitializeReturns = {
	users: UserData[],
	groups: GroupData[],
	subjects: SubjectData[],
	teacherSubjects: TeacherSubjectData[],
	saveUser: (updatedUser: UserData, selectedSubjectIds: string[]) => Promise<void>,
	deleteUser: (userId: string) => Promise<void>,
	loading: boolean,
}

const useInitialize = (): UseInitializeReturns => {
	const [listUsers] = useLazyListUsers()
	const [listSubjects] = useLazyListSubjects()
	const [listGroups] = useLazyListGroups()
	const [listTeacherSubjects] = useLazyListTeacherSubjects()
	const [createTeacherSubjects] = useLazyCreateTeacherSubjects()
	const [deleteTeacherSubjects] = useLazyDeleteTeacherSubjects()
	const [deleteUserQuery] = useLazyDeleteUser()
	const [createUserQuery] = useLazyCreateUser()
	const [updateUserQuery] = useLazyUpdateUser()
	const [createGroupMembers] = useLazyCreateGroupMembers()
	const [deleteGroupMembers] = useLazyDeleteGroupMembers()

	const [users, setUsers] = useState<UserData[]>([])
	const [groups, setGroups] = useState<GroupData[]>([])
	const [subjects, setSubjects] = useState<SubjectData[]>([])
	const [teacherSubjects, setTeacherSubjects] = useState<TeacherSubjectData[]>([])
	const [loading, setLoading] = useState<boolean>(true)

	const fetchData = useCallback(async () => {
		try {
			setLoading(true)

			const [usersResponse, subjectsResponse, groupsResponse, teacherSubjectsResponse] = await Promise.all([
				listUsers({}),
				listSubjects({}),
				listGroups({}),
				listTeacherSubjects({}),
			])

			setUsers(remapApiUsersToUsersList(usersResponse.data?.users || []))
			setSubjects(subjectsResponse.data?.subjects || [])
			setGroups(groupsResponse.data?.groups || [])
			setTeacherSubjects(teacherSubjectsResponse.data?.teacherSubjects || [])
		}
		catch (error) {
			console.error('Error fetching data:', error)
		}
		finally {
			setLoading(false)
		}
	}, [listUsers, listSubjects, listGroups, listTeacherSubjects])

	useEffect(() => {
		fetchData()
	}, [fetchData])

	const saveUser = async (updatedUser: UserData, selectedSubjectIds: string[]) => {
		try {
			const existingUser = users.find(user => user.userId === updatedUser.userId)
			const currentUserGroups = (existingUser && groups.filter(
				group => existingUser.groupIds.includes(group.groupId),
			)) ?? []
			const currentTeacherSubjects = (existingUser && teacherSubjects.filter(
				teacherSubject => existingUser.userId === teacherSubject.teacherId,
			)) ?? []

			let userId = updatedUser.userId
			let groupIdsToAdd: string[] = []
			let groupIdsToRemove: string[] = []
			let subjectIdsToAdd: string[] = []
			let subjectIdsToRemove: string[] = []
			let action: 'creating' | 'updating' = 'creating'

			if (existingUser) {
				await updateUserQuery({...updatedUser, role: remapUserRoleToApiUserRole(updatedUser.role)})

				groupIdsToAdd = updatedUser.groupIds.filter(
					selectedGroupId => !currentUserGroups.some(currentGroup => currentGroup.groupId === selectedGroupId),
				)
				groupIdsToRemove = currentUserGroups
					.filter(group => !updatedUser.groupIds.some(selectedGroupId => selectedGroupId === group.groupId))
					.map(group => group.groupId)

				subjectIdsToAdd = selectedSubjectIds.filter(
					selectedSubjectId => !currentTeacherSubjects.some(currentTeacherSubject => currentTeacherSubject.subjectId === selectedSubjectId),
				)
				subjectIdsToRemove = currentTeacherSubjects
					.filter(currentTeacherSubject => !selectedSubjectIds.some(selectedSubjectId => selectedSubjectId === currentTeacherSubject.subjectId))
					.map(ts => ts.subjectId)

				action = 'updating'
			}
			else {
				if (!updatedUser.password) {
					throw new Error('User password cannot be undefined')
				}

				const response = await createUserQuery({
					...updatedUser,
					role: remapUserRoleToApiUserRole(updatedUser.role),
					password: updatedUser.password,
				})
				if (response.isError || !response.data) {
					throw new Error('Error in user creating')
				}

				userId = response.data.userId
				groupIdsToAdd = updatedUser.groupIds
				subjectIdsToAdd = selectedSubjectIds
				action = 'creating'
			}

			if (subjectIdsToAdd.length > 0) {
				await createTeacherSubjects({
					teacherSubjects: subjectIdsToAdd.map(subjectId => ({subjectId, teacherId: userId})),
				})
			}
			if (subjectIdsToRemove.length > 0) {
				const tsIdsToRemove = currentTeacherSubjects
					.filter(
						teacherSubject => subjectIdsToRemove.includes(teacherSubject.subjectId),
					)
					.map(teacherSubject => teacherSubject.teacherSubjectId)

				await deleteTeacherSubjects({teacherSubjectIds: tsIdsToRemove})
			}
			if (groupIdsToAdd.length > 0) {
				await createGroupMembers({groupIds: groupIdsToAdd, userIds: [userId]})
			}
			if (groupIdsToRemove.length > 0) {
				await deleteGroupMembers({groupIds: groupIdsToRemove, userIds: [userId]})
			}

			if (action === 'creating') {
				message.success('Пользователь успешно создан.')
			}
			if (action === 'updating') {
				message.success('Пользователь успешно обновлён.')
			}

			await fetchData()
		}
		catch (error) {
			message.error('Что-то пошло не так. Поробуйте повторить попытку позже.')
			console.error('Error saving user:', error)
		}
	}

	const deleteUser = async (userId: string) => {
		try {
			await deleteUserQuery({userId})
			setUsers(prev => prev.filter(user => user.userId !== userId))
		}
		catch (error) {
			console.error('Error deleting user:', error)
		}
	}

	return {
		users,
		groups,
		subjects,
		teacherSubjects,
		saveUser,
		deleteUser,
		loading,
	}
}

export {
	useInitialize,
}
