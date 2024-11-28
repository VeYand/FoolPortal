import {AddStudentsToGroupRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

type AddStudentToGroupInput = {
	request: AddStudentsToGroupRequest,
	groupId: string,
}

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		addStudentsToGroup: builder.query<void, AddStudentToGroupInput>({
			queryFn: async request => await studentPortalApi.get().userApi.addStudentsToGroup(request.groupId, request.request),
		}),
	}),
})

export const {useLazyAddStudentsToGroupQuery: useLazyAddStudentsToGroup} = api
