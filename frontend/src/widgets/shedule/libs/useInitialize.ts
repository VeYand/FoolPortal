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
import {formatDateToISO} from '../../../shared/libs'


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
			const {data: lessonsData} = await listLessons({startTime: formatDateToISO(startTime), endTime: formatDateToISO(endTime)})

			const {data: locationsData} = await listLocations({})
			const locationsList = remapApiLocationsToLocationsList(locationsData?.locations ?? [])
			setLocations(locationsList)

			const {data: coursesData} = await listCourses({})
			const coursesList = remapApiCoursesToCoursesList(coursesData?.courses ?? [])
			setCourses(coursesList)

			const {data: teacherSubjectsData} = await listTeacherSubjects({})
			const teacherSubjectsList = remapApiTeacherSubjectsToTeacherSubjectsList(teacherSubjectsData?.teacherSubjects ?? [])
			setTeacherSubjects(teacherSubjectsList)

			const {data: subjectsData} = await listSubjects({})
			const subjectsList = remapApiSubjectsToSubjectsList(subjectsData?.subjects ?? [])
			setSubjects(subjectsList)

			const {data: groupsData} = await listGroups({})
			const groupsList = remapApiGroupsToGroupsList(groupsData?.groups ?? [])
			setGroups(groupsList)

			const {data: usersData} = await listUsers({})
			const usersList = remapApiUsersToUsersList(usersData?.users ?? [])
			setUsers(usersList)

			setLessons(remapApiLessonsToLessonsList({
				lessons: lessonsData?.lessons ?? [],
				coursesList,
				teacherSubjectsList,
				subjectsList,
				groupsList,
				usersList,
			}))

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