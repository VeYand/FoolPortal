import {ListUsersSpec, UsersList} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listUsers: builder.query<UsersList, ListUsersSpec>({
			queryFn: async request => await studentPortalApi.get().userApi.listUsers({
				groupIds: request.groupIds,
			}),
		}),
	}),
})

export const {useLazyListUsersQuery: useLazyListUsers} = api
