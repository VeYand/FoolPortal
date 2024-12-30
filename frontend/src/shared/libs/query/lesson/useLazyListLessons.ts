import {LessonsList, ListLessonsRequest} from 'shared/api'
import {apiSlice, studentPortalApi} from 'shared/redux/api'

const api = apiSlice.injectEndpoints({
	endpoints: builder => ({
		listLessons: builder.query<LessonsList, ListLessonsRequest>({
			queryFn: async request => await studentPortalApi.get().lessonApi.listLessons(request),
		}),
	}),
})

export const {useLazyListLessonsQuery: useLazyListLessons} = api
