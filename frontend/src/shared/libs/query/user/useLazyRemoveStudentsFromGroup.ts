import {apiSlice, studentPortalApi} from 'shared/redux/api'

type RemoveStudentsFromGroupInput = {
	studentIds: string[],
	groupId: string,
}

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		removeStudentsFromGroup: builder.query<void, RemoveStudentsFromGroupInput>({
			queryFn: async request => await studentPortalApi.get().userApi.removeStudentsFromGroup(request.groupId, {
				studentIds: request.studentIds,
			}),
		}),
	}),
})

export const {useLazyRemoveStudentsFromGroupQuery: useLazyRemoveStudentsFromGroup} = api
