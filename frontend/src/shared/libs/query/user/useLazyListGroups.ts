import {GroupsList} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listGroups: builder.query<GroupsList, void>({
			queryFn: async () => await studentPortalApi.get().userApi.listGroups(),
		}),
	}),
})

export const {useLazyListGroupsQuery: useLazyListGroups} = api
