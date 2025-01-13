import {ListUsersSpec, ListUsers200Response} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listUsers: builder.query<ListUsers200Response, ListUsersSpec>({
			queryFn: async request => await studentPortalApi.get().userApi.listUsers(request),
		}),
	}),
})

export const {useLazyListUsersQuery: useLazyListUsers} = api
