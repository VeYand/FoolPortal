import {UpdateUserRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		updateUser: builder.query<void, UpdateUserRequest>({
			queryFn: async request => await studentPortalApi.get().userApi.updateUser(request),
		}),
	}),
})

export const {useLazyUpdateUserQuery: useLazyUpdateUser} = api
