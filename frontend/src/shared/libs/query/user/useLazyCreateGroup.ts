import {CreateGroupRequest, CreateGroupResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createGroup: builder.query<CreateGroupResponse, CreateGroupRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.createGroup(request),
		}),
	}),
})

export const {useLazyCreateGroupQuery: useLazyCreateGroup} = api
