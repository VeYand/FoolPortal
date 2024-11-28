import {UpdateGroupRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		updateGroup: builder.query<void, UpdateGroupRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.updateGroup(request),
		}),
	}),
})

export const {useLazyUpdateGroupQuery: useLazyUpdateGroup} = api
