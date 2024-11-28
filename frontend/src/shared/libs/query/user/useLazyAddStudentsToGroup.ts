import {apiSlice, studentPortalApi} from 'shared/redux/api'

type AddStudentToGroupInput = {
	studentIds: string[],
	groupId: string,
}

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		addStudentsToGroup: builder.query<void, AddStudentToGroupInput>({
			queryFn: async request => await studentPortalApi.get().userApi.addStudentsToGroup(request.groupId, {
				studentIds: request.studentIds,
			}),
		}),
	}),
})

export const {useLazyAddStudentsToGroupQuery: useLazyAddStudentsToGroup} = api
