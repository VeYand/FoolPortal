import {message} from 'antd'
import {userEntitySlice} from 'entities/user'
import {useEffect, useState, useCallback} from 'react'
import {UpdateUserRequest} from 'shared/api'
import {areListsEqual} from 'shared/libs'
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
import {remapUserRolesToApiUserRoles} from 'shared/libs/remmapers/remapUserDataToApiUser'
import {useAppDispatch, useAppSelector} from 'shared/redux'
import {UserData, GroupData, SubjectData, TeacherSubjectData, USER_ROLE} from 'shared/types'

type UseInitializeReturns = {
	users: UserData[],
	groups: GroupData[],
	subjects: SubjectData[],
	teacherSubjects: TeacherSubjectData[],
	saveUser: (updatedUser: UserData, selectedSubjectIds: string[]) => Promise<void>,
	deleteUser: (userId: string) => Promise<void>,
	loading: boolean,
}

type Sort = 'ASC' | 'DESC'

type OrderField = 'name' | 'email'


const useInitialize = (usersSort?: Sort, orderField?: OrderField, usersPage?: number, pageLimit?: number, roles?: USER_ROLE[]): UseInitializeReturns => {
	const dispatch = useAppDispatch()
	const currentUser = useAppSelector(state => state.userEntity.user)

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

	const fetchData = useCallback(async (sort?: Sort, field?: OrderField, page?: number, limit?: number, rolesData?: USER_ROLE[]) => {
		try {
			setLoading(true)

			const [usersResponse, subjectsResponse, groupsResponse, teacherSubjectsResponse] = await Promise.all([
				listUsers({
					ascOrder: sort ? sort === 'ASC' : undefined,
					orderField: field,
					page,
					limit,
					roles: remapUserRolesToApiUserRoles(rolesData),
				}),
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
		fetchData(usersSort, orderField, usersPage, pageLimit, roles)
	}, [fetchData, orderField, pageLimit, roles, usersPage, usersSort])

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
				await updateUserQuery(constructUpdateUserRequest(existingUser, updatedUser))
				if (existingUser.userId === currentUser.userId) {
					dispatch(userEntitySlice.actions.setUser(updatedUser))
				}
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
					message.error('Пользовательский пароль не может быть пустым')
					return
				}

				const response = await createUserQuery({
					...updatedUser,
					role: remapUserRoleToApiUserRole(updatedUser.role),
					password: updatedUser.password,
					imageData: updatedUser.imageSrc,
				})
				if (response.isError || !response.data) {
					message.error('Не удалось создать пользователя, попробуйте позже')
					return
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
			message.success('Пользователь успешно удалён.')
		}
		catch (error) {
			message.error('Что-то пошло не так. Поробуйте повторить попытку позже.')
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

const constructUpdateUserRequest = (existingUser: UserData, updatedUser: UserData): UpdateUserRequest => {
	const data: UpdateUserRequest = {
		userId: updatedUser.userId,
	}

	if (updatedUser.firstName !== undefined && existingUser.firstName !== updatedUser.firstName) {
		data.firstName = updatedUser.firstName
	}

	if (updatedUser.lastName !== undefined && existingUser.lastName !== updatedUser.lastName) {
		data.lastName = updatedUser.lastName
	}

	if (updatedUser.patronymic !== undefined && existingUser.patronymic !== updatedUser.patronymic) {
		data.patronymic = updatedUser.patronymic
	}

	if (updatedUser.role !== undefined && existingUser.role !== updatedUser.role) {
		data.role = remapUserRoleToApiUserRole(updatedUser.role)
	}

	if (updatedUser.imageSrc !== undefined && existingUser.imageSrc !== updatedUser.imageSrc) {
		data.imageData = updatedUser.imageSrc
	}

	if (updatedUser.email !== undefined && existingUser.email !== updatedUser.email) {
		data.email = updatedUser.email
	}

	if (updatedUser.groupIds !== undefined && areListsEqual(existingUser.groupIds, updatedUser.groupIds)) {
		data.groupIds = updatedUser.groupIds
	}

	if (updatedUser.password !== undefined && existingUser.password !== updatedUser.password) {
		data.password = updatedUser.password
	}

	return data
}


export {
	useInitialize,
}
