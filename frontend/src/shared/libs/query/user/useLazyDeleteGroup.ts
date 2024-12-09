import {DeleteGroupRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteGroup: builder.query<EmptyResponse, DeleteGroupRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.deleteGroup(request),
		}),
	}),
})

export const {useLazyDeleteGroupQuery: useLazyDeleteGroup} = api
