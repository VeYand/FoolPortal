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
	useLazyUpdateUser, useLazyCreateGroupMembers, useLazyDeleteGroupMembers,
} from 'shared/libs/query'
import {remapUserRoleToApiUserRole} from 'shared/libs/remmapers'
import {remapApiUsersToUsersList} from 'shared/libs/remmapers/remapApiUserToUserData'
import {UserData, GroupData, SubjectData, TeacherSubjectData} from 'shared/types'

type UseInitializeReturns = {
	users: UserData[],
	groups: GroupData[],
	subjects: SubjectData[],
	teacherSubjects: TeacherSubjectData[],
	saveUser: (updatedUser: UserData, updatedTeacherSubjects: TeacherSubjectData[]) => Promise<void>,
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

	const saveUser = async (updatedUser: UserData, updatedTeacherSubjects: TeacherSubjectData[]) => {
		try {
			const existingUser = users.find(user => user.userId === updatedUser.userId)
			const currentUserGroups = (existingUser && groups.filter(
				group => existingUser.groupIds.includes(group.groupId),
			)) ?? []

			let userId = updatedUser.userId
			let groupIdsToAdd: string[] = []
			let groupIdsToRemove: string[] = []

			if (existingUser) {
				await updateUserQuery({...updatedUser, role: remapUserRoleToApiUserRole(updatedUser.role)})

				groupIdsToAdd = updatedUser.groupIds.filter(
					updatedGroupId => !currentUserGroups.some(currentGroup => currentGroup.groupId === updatedGroupId),
				)
				groupIdsToRemove = currentUserGroups
					.filter(cts => !updatedUser.groupIds.some(uts => uts === cts.groupId))
					.map(group => group.groupId)
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
			}

			const currentTeacherSubjects = teacherSubjects.filter(
				ts => ts.teacherId === userId,
			)
			const teacherSubjectToCreate = updatedTeacherSubjects.filter(
				uts => !currentTeacherSubjects.some(cts => cts.subjectId === uts.subjectId),
			)
			const teacherSubjectToDelete = currentTeacherSubjects.filter(
				cts => !updatedTeacherSubjects.some(uts => uts.subjectId === cts.subjectId),
			)

			if (teacherSubjectToCreate.length > 0) {
				await createTeacherSubjects({teacherSubjects: teacherSubjectToCreate})
			}
			if (teacherSubjectToDelete.length > 0) {
				const idsToDelete = teacherSubjectToDelete.map(ts => ts.teacherSubjectId)
				await deleteTeacherSubjects({teacherSubjectIds: idsToDelete})
			}
			if (groupIdsToAdd.length > 0) {
				await createGroupMembers({groupIds: groupIdsToAdd, userIds: [userId]})
			}
			if (groupIdsToRemove.length > 0) {
				await deleteGroupMembers({groupIds: groupIdsToRemove, userIds: [userId]})
			}

			await fetchData()
		}
		catch (error) {
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
