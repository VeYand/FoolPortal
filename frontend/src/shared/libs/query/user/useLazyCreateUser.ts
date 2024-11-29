import {CreateUserRequest, CreateUserResponse} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		createUser: builder.query<CreateUserResponse, CreateUserRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.createUser(request),
		}),
	}),
})

export const {useLazyCreateUserQuery: useLazyCreateUser} = api
