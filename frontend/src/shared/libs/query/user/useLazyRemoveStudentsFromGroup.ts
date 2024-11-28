import {RemoveStudentsFromGroupRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

type RemoveStudentsFromGroupInput = {
	request: RemoveStudentsFromGroupRequest,
	groupId: string,
}

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		removeStudentsFromGroup: builder.query<void, RemoveStudentsFromGroupInput>({
			queryFn: async request => await studentPortalApi.get().userApi.removeStudentsFromGroup(request.groupId, request.request),
		}),
	}),
})

export const {useLazyRemoveStudentsFromGroupQuery: useLazyRemoveStudentsFromGroup} = api
