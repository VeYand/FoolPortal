import {useEffect, useState} from 'react'
import {
	useLazyListLocations,
	useLazyListLessons,
	useLazyListCourses,
	useLazyListGroups,
	useLazyListUsers, useLazyListTeacherSubjects, useLazyListSubjects,
} from 'shared/libs/query'
import {
	remapApiCoursesToCoursesList,
	remapApiGroupsToGroupsList,
	remapApiLessonsToLessonsList,
	remapApiLocationsToLocationsList,
	remapApiTeacherSubjectsToTeacherSubjectsList,
	remapApiSubjectsToSubjectsList,
	remapApiUsersToUsersList,
} from 'shared/libs/remmapers'
import {
	UserData,
	CourseData,
	GroupData,
	LessonData,
	LocationData,
	TeacherSubjectData,
	SubjectData,
} from 'shared/types/types'


type InitializedData = {
	loading: boolean,
	lessons: LessonData[],
	locations: LocationData[],
	courses: CourseData[],
	teacherSubjects: TeacherSubjectData[],
	subjects: SubjectData[],
	groups: GroupData[],
	users: UserData[],
}

const useInitialize = (startTime: Date, endTime: Date): InitializedData => {
	const [listLessons] = useLazyListLessons()
	const [listLocations] = useLazyListLocations()
	const [listCourses] = useLazyListCourses()
	const [listTeacherSubjects] = useLazyListTeacherSubjects()
	const [listSubjects] = useLazyListSubjects()
	const [listGroups] = useLazyListGroups()
	const [listUsers] = useLazyListUsers()

	const [loading, setLoading] = useState(true)
	const [lessons, setLessons] = useState<LessonData[]>([])
	const [locations, setLocations] = useState<LocationData[]>([])
	const [courses, setCourses] = useState<CourseData[]>([])
	const [teacherSubjects, setTeacherSubjects] = useState<TeacherSubjectData[]>([])
	const [subjects, setSubjects] = useState<SubjectData[]>([])
	const [groups, setGroups] = useState<GroupData[]>([])
	const [users, setUsers] = useState<UserData[]>([])

	useEffect(() => {
		const callback = async () => {
			const {data: lessonsData} = await listLessons({startTime: startTime.toISOString(), endTime: endTime.toISOString()})
			setLessons(remapApiLessonsToLessonsList(lessonsData?.lessons ?? []))

			const locationIds = lessonsData?.lessons.map(lesson => lesson.locationId) ?? []
			const {data: locationsData} = await listLocations({locationIds: locationIds})
			setLocations(remapApiLocationsToLocationsList(locationsData?.locations ?? []))

			const courseIds = lessonsData?.lessons.map(lesson => lesson.courseId) ?? []
			const {data: coursesData} = await listCourses({courseIds})
			setCourses(remapApiCoursesToCoursesList(coursesData?.courses ?? []))

			const {data: teacherSubjectsData} = await listTeacherSubjects({courseIds})
			setTeacherSubjects(remapApiTeacherSubjectsToTeacherSubjectsList(teacherSubjectsData?.teacherSubjects ?? []))

			const {data: subjectsData} = await listSubjects({})
			setSubjects(remapApiSubjectsToSubjectsList(subjectsData?.subjects ?? []))

			const groupIds = coursesData?.courses.map(course => course.groupId) ?? []
			const {data: groupsData} = await listGroups({groupIds})
			setGroups(remapApiGroupsToGroupsList(groupsData?.groups ?? []))

			const {data: usersData} = await listUsers({})
			setUsers(remapApiUsersToUsersList(usersData?.users ?? []))
			setLoading(false)
		}

		setLoading(true)
		callback()
	}, [])

	return {
		loading,
		lessons,
		locations,
		courses,
		teacherSubjects,
		subjects,
		groups,
		users,
	}
}

export {
	useInitialize,
}