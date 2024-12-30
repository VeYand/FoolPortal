import {CourseData as ApiCourseData} from 'shared/api'
import {CourseData} from '../../types/types'

const remapApiCourseToCourseData = (course: ApiCourseData): CourseData => ({
	courseId: course.courseId,
	teacherSubjectId: course.teacherSubjectId,
	groupId: course.groupId,
})

const remapApiCoursesToCoursesList = (courses: ApiCourseData[]): CourseData[] => courses.map(
	course => remapApiCourseToCourseData(course),
)

export {
	remapApiCoursesToCoursesList,
}