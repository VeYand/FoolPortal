import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listGroups: builder.query({
			queryFn: async () => await studentPortalApi.get().userApi.listGroups(),
		}),
	}),
})

export const {useLazyListGroupsQuery: useLazyListGroups} = api
