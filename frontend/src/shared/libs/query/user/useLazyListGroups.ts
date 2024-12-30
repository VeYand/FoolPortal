import {GroupsList as ApiGroupsList, ListGroupsRequest as ApiListGroupsRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listGroups: builder.query<ApiGroupsList, ApiListGroupsRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.listGroups(request),
		}),
	}),
})

export const {useLazyListGroupsQuery: useLazyListGroups} = api
