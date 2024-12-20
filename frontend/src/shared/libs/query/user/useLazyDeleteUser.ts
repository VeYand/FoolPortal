import {DeleteUserRequest, EmptyResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		deleteUser: builder.query<EmptyResponse, DeleteUserRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.deleteUser(request),
		}),
	}),
})

export const {useLazyDeleteUserQuery: useLazyDeleteUser} = api
