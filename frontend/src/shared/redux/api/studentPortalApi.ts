import {AuthorizationApi, Configuration, LessonApi, SubjectApi, UserApi} from 'shared/api'
import {ApiWrapper} from './apiWrapper'

type StudentPortalApiType = {
	authorizationApi: AuthorizationApi,
	userApi: UserApi,
	subjectApi: SubjectApi,
	lessonApi: LessonApi,
}

// @ts-expect-error
const baseUrl = import.meta.env?.VITE_APP_BASE_URL
if (!baseUrl) {
	throw new Error('Base url is not configured')
}

let resultApi: StudentPortalApiType | null = null

const studentPortalApi: ApiWrapper<StudentPortalApiType> = {
	init: () => {
		const configuration = new Configuration({basePath: baseUrl})
		resultApi = {
			authorizationApi: new AuthorizationApi(configuration),
			userApi: new UserApi(configuration),
			subjectApi: new SubjectApi(configuration),
			lessonApi: new LessonApi(configuration),
		}
	},
	get: () => {
		if (!resultApi) {
			throw new Error('StudentPortalApi is not initialized')
		}
		return resultApi
	},
}

export {
	studentPortalApi,
}